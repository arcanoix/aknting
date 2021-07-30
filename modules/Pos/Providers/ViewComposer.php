<?php

namespace Modules\Pos\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Pos\Http\ViewComposers\AddBarcodeFieldInItem;
use Modules\Pos\Http\ViewComposers\CalculateFinancialsInReceipt;
use Modules\Pos\Http\ViewComposers\ShowChangeAmountWithMinusInTransactions;

class ViewComposer extends ServiceProvider
{
    public function boot()
    {
        View::composer(['pos::orders.receipt'], CalculateFinancialsInReceipt::class);

        View::composer(['components.documents.show.transactions'], ShowChangeAmountWithMinusInTransactions::class);

        View::composer([
            'common.items.create',
            'common.items.edit',
        ], AddBarcodeFieldInItem::class);
    }
}
