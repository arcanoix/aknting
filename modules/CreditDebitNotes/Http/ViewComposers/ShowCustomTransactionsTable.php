<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\DebitNote;

class ShowCustomTransactionsTable
{
    public function compose(View $view)
    {
        $view_data = $view->getData();

        if ($view_data['type'] !== CreditNote::TYPE && $view_data['type'] !== DebitNote::TYPE) {
            return;
        }

        $view->setPath(view('credit-debit-notes::partials.transactions')->getPath());
    }
}
