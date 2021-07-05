<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Models\Document\Document;
use Modules\CreditDebitNotes\Http\Requests\CreditsTransaction as Request;
use App\Models\Setting\Currency;
use Illuminate\Http\JsonResponse;
use Modules\CreditDebitNotes\Jobs\Credits\CreateCreditsTransaction;
use Modules\CreditDebitNotes\Services\Credits;

class InvoiceCreditsTransactions extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-credit-debit-notes-credit-notes')->only('create', 'store');
    }

    public function create(Document $invoice, Credits $credits): JsonResponse
    {
        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code')->toArray();

        $currency = Currency::where('code', $invoice->currency_code)->first();

        $paid = $invoice->paid;

        // Get Invoice Totals
        foreach ($invoice->totals as $invoice_total) {
            $invoice->{$invoice_total->code} = $invoice_total->amount;
        }

        if (!empty($paid)) {
            $grand_total = $invoice->total - $paid;
        } else {
            $total = money($invoice->total, $currency->code, true)->format();
            $grand_total = money($total, $currency->code)->getAmount();
        }

        $applied_credits = $credits->getAppliedCredits($invoice);
        if (!empty($applied_credits)) {
            $grand_total = $grand_total - $applied_credits;
        }

        $available_credits = max($credits->getAvailableCredits($invoice->contact_id), 0);

        $invoice->grand_total = round(min($available_credits, $grand_total), $currency->precision);

        $html = view('credit-debit-notes::modals.invoices.credit', compact('invoice', 'currencies', 'currency'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('credit-debit-notes::invoices.use_credits')." ({$currency->symbol}{$available_credits} available)",
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

    public function store(Document $invoice, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateCreditsTransaction($invoice, $request));

        if ($response['success']) {
            $response['redirect'] = route('invoices.show', $invoice->id);

            $message = trans('credit-debit-notes::invoices.credits_used');

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
