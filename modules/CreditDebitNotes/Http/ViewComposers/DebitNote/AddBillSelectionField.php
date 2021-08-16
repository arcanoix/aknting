<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\DebitNote;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\DebitNote;

class AddBillSelectionField
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

        if ($view_data['type'] !== DebitNote::TYPE) {
            return;
        }

        $document = $view_data['document'] ?? null;
        $contact = $view_data['contact'] ?? null;

        $bills = optional($document)->vendor_bills ?? [];
        $bill_id = optional($document)->bill_id ?? '';

        $view->getFactory()->startPush(
            'document_number_input_end',
            view('credit-debit-notes::partials.debit_note.bill_selection', compact('document', 'contact', 'bills', 'bill_id'))
        );
    }

}
