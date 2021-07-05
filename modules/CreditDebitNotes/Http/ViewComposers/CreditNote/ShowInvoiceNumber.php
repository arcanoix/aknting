<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use Illuminate\Support\Str;
use Illuminate\View\View;

class ShowInvoiceNumber
{
    public function compose(View $view)
    {
        $credit_note = $view->getData()['credit_note'];

        if(!$credit_note->invoice_number) {
            return;
        }

        $print = Str::contains($view->name(), 'print');

        $invoice_route = $this->getInvoiceRoute($view, $credit_note);

        $view->getFactory()->startPush(
            'issued_at_input_end',
            view('credit-debit-notes::partials.credit_note.invoice_number', compact('credit_note', 'print', 'invoice_route'))
        );
    }

    private function getInvoiceRoute(View $view, $credit_note): string
    {
        if (isset($view->getData()['invoice_signed_url'])) {
            return $view->getData()['invoice_signed_url'];
        }

        if (Str::contains($view->name(), 'portal')) {
            return route('portal.invoices.show', ['invoice' => $credit_note->invoice_id]);
        }

        return route('invoices.show', ['invoice' => $credit_note->invoice_id]);
    }

}
