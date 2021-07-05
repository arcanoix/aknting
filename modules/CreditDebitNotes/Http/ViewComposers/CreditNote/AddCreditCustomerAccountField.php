<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use Illuminate\View\View;

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
        $credit_customer_account = true;

        if (isset($view->getData()['credit_note'])) {
            $credit_customer_account = $view->getData()['credit_note']->credit_customer_account;
        }

        $view->getFactory()->startPush(
            'invoice_id_input_end',
            view('credit-debit-notes::fields.credit_customer_account', compact('credit_customer_account'))
        );
    }

}
