<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\DebitNote;

class AddOriginalContactIdField
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

        if ($view_data['type'] !== CreditNote::TYPE && $view_data['type'] !== DebitNote::TYPE) {
            return;
        }

        $contact = $view_data['contact'] ?? null;

        $view->getFactory()->startPush(
            'document_number_input_end',
            view('credit-debit-notes::partials.original_contact_id', compact('contact'))
        );
    }
}
