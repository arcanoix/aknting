<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Bill;

use Illuminate\View\View;

class ShowCreateDebitNoteMenuItem
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
            view('credit-debit-notes::partials.bill.create_debit_note_button', ['bill' => $view->getData()['bill']])
        );
    }

}
