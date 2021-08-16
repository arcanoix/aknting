<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use App\Models\Document\Document;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;

class PrefillCreditNote
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view_data = $view->getData();

        if ($view_data['type'] !== CreditNote::TYPE) {
            return;
        }

        if (!empty($view_data['document'])) {
            return;
        }

        if (!$invoice_id = request()->query('invoice', null)) {
            return;
        }

        $invoice = Document::invoice()->findOrFail($invoice_id);

        $document = new \stdClass();
        $document->credit_customer_account = true;
        $document->invoice_id = $invoice_id;
        $document->category_id = $invoice->category_id;
        $document->customer_invoices = $invoice->contact->invoices()
            ->whereIn('status', ['sent', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        $contact = $invoice->contact;

        $view->with(compact('document', 'contact'));
    }
}
