<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\CreditNote;

use Illuminate\View\View;

class TextOverride
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['text_override' => [
            'items' => setting('credit-debit-notes.credit_note.item_name'),
            'quantity' => setting('credit-debit-notes.credit_note.quantity_name'),
            'price' => setting('credit-debit-notes.credit_note.price_name'),
        ]]);
    }

}
