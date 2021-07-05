<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Invoice;

use Illuminate\View\View;

class ShowCreateCreditNoteButton
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->getFactory()->startPush(
            'add_new_button_start',
            view('credit-debit-notes::partials.invoice.create_credit_note_button', ['invoice' => $view->getData()['invoice']])
        );
    }

}
