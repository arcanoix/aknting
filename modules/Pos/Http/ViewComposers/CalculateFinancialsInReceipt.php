<?php

namespace Modules\Pos\Http\ViewComposers;

use App\Models\Auth\User;
use Illuminate\View\View;

class CalculateFinancialsInReceipt
{
    public function compose(View $view)
    {
        $order = $view->getData()['order'];

        $served_by = User::where('id', $order->created_by)->pluck('name')->first();

        $payments = $order->transactions
            ->where('type', 'income')
            ->map(function ($transaction) {
                return [
                    // TODO: keep additional transactions data instead of comparing with a setting
                    'type'   => $transaction->account_id == setting('pos.general.cash_account_id') ? trans('general.cash') : trans('pos::pos.card'),
                    'amount' => $transaction->amount,
                ];
            });

        // TODO: keep additional order data instead of getting from transactions
        $change = $order->transactions
            ->where('type', 'expense')
            ->pluck('amount')
            ->first();

        $total = $order->totals
            ->where('code', 'total')
            ->pluck('amount')
            ->first();

        $view->with('served_by', $served_by)
            ->with('payments', $payments)
            ->with('change', $change)
            ->with('total', $total);
    }
}
