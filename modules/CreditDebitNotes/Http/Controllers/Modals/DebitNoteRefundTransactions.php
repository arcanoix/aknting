<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Utilities\Modules;
use Modules\CreditDebitNotes\Http\Requests\RefundTransaction as Request;
use Modules\CreditDebitNotes\Models\DebitNote;
use App\Models\Setting\Currency;
use Illuminate\Http\JsonResponse;
use Modules\CreditDebitNotes\Jobs\Refund\CreateRefundTransaction;
use Throwable;

class DebitNoteRefundTransactions extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-credit-debit-notes-debit-notes')->only('create', 'store');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DebitNote $debit_note
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(DebitNote $debit_note)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::income()->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $debit_note->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $html = view('credit-debit-notes::modals.debit_note.refund', compact('debit_note', 'accounts', 'currencies', 'currency', 'categories', 'payment_methods'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('credit-debit-notes::debit_notes.receive_refund'),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary'
                    ],
                    'confirm' => [
                        'text' => trans('general.save'),
                        'class' => 'btn-success'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DebitNote $debit_note
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(DebitNote $debit_note, Request $request)
    {
        $response = $this->ajaxDispatch(new CreateRefundTransaction($debit_note, $request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.debit-notes.show', $debit_note->id);

            $message = trans('credit-debit-notes::debit_notes.messages.refund_was_received');

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
