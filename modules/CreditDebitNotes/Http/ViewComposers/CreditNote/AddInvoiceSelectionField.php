<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;

class AddInvoiceSelectionField
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view_data = $view->getData();

        if ($view_data['type'] !== CreditNote::TYPE) {
            return;
        }

        $document = $view_data['document'] ?? null;

        $contact = $view_data['contact'] ?? null;

        $invoices = optional($document)->customer_invoices ?? [];
        $invoice_id = optional($document)->invoice_id ?? '';

        $view->getFactory()->startPush(
            'document_number_input_end',
            view('credit-debit-notes::partials.credit_note.invoice_selection', compact('document', 'contact', 'invoices', 'invoice_id'))
        );
    }
}
