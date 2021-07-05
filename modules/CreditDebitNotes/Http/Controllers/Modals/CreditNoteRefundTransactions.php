<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Utilities\Modules;
use Modules\CreditDebitNotes\Http\Requests\RefundTransaction as Request;
use Modules\CreditDebitNotes\Models\CreditNote;
use App\Models\Setting\Currency;
use Illuminate\Http\JsonResponse;
use Modules\CreditDebitNotes\Jobs\Refund\CreateRefundTransaction;
use Throwable;

class CreditNoteRefundTransactions extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-credit-debit-notes-credit-notes')->only('create', 'store');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreditNote $credit_note
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(CreditNote $credit_note)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $categories = Category::expense()->enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $credit_note->currency_code)->first();

        $payment_methods = Modules::getPaymentMethods();

        $html = view('credit-debit-notes::modals.credit_note.refund', compact('credit_note', 'accounts', 'currencies', 'currency', 'categories', 'payment_methods'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('credit-debit-notes::credit_notes.make_refund'),
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
     * @param CreditNote $credit_note
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(CreditNote $credit_note, Request $request)
    {
        $response = $this->ajaxDispatch(new CreateRefundTransaction($credit_note, $request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.credit-notes.show', $credit_note->id);

            $message = trans('credit-debit-notes::credit_notes.messages.refund_was_made');

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
