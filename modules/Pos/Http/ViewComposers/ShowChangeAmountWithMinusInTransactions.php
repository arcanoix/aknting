<?php

namespace Modules\Pos\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Pos\Models\Order;

class ShowChangeAmountWithMinusInTransactions
{
    public function compose(View $view)
    {
        $viewData = $view->getData();

        if ($viewData['type'] !== Order::TYPE) {
            return;
        }

        $transactions = $viewData['transactions']
            ->map(function ($transaction) {
                if ($transaction->isExpense()) {
                    $transaction->amount = -$transaction->amount;
                }

                return $transaction;
            });

        $view->with('transactions', $transactions);
    }
}
