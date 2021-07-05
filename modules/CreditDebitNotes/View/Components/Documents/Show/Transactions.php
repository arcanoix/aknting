<?php

namespace Modules\CreditDebitNotes\View\Components\Documents\Show;

use App\Abstracts\View\Components\DocumentShow as Component;

class Transactions extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('credit-debit-notes::components.documents.show.transactions');
    }
}
