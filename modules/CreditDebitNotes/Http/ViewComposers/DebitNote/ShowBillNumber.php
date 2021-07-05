<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\DebitNote;

use Illuminate\Support\Str;
use Illuminate\View\View;

class ShowBillNumber
{
    public function compose(View $view)
    {
        $debit_note = $view->getData()['debit_note'];

        $print = Str::contains($view->name(), 'print');

        $view->getFactory()->startPush(
            'issued_at_input_end',
            view('credit-debit-notes::partials.debit_note.bill_number', compact('debit_note', 'print'))
        );
    }

}
