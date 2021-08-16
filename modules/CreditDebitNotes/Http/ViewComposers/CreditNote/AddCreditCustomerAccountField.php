<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;

class AddCreditCustomerAccountField
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

        $document = $view_data['document'] ?? null;

        $credit_customer_account = $document ? $document->credit_customer_account : true;

        $view->getFactory()->startPush(
            'invoice_id_input_end',
            view('credit-debit-notes::partials.credit_note.credit_customer_account', compact('credit_customer_account'))
        );
    }

}
