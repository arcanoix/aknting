<?php

namespace Modules\Pos\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Currency;

class Main extends Controller
{
    public function __invoke()
    {
        $currency = Currency::where('code', setting('default.currency'))->first();

        $translations = [
            'customer'       => trans_choice('general.customers', 1),
            'payment'        => trans_choice('general.payments', 1),
            'qty'            => trans('pos::pos.qty'),
            'disc'           => trans('pos::pos.disc'),
            'price'          => trans('pos::pos.price'),
            'cancel'         => trans('general.cancel'),
            'clear_customer' => trans('general.clear') . ' ' . trans_choice('general.customers', 1),
            'name'           => trans('general.name'),
            'address'        => trans('general.address'),
            'phone'          => trans('general.phone'),
            'email'          => trans('general.email'),
            'total'          => trans('pos::pos.total'),
            'back'           => trans('pos::pos.back'),
            'validate'       => trans('pos::pos.validate'),
            'remaining'      => trans('pos::pos.remaining'),
            'change'         => trans('general.change'),
            'total_due'      => trans('pos::pos.total_due'),
            'cash'           => trans('general.cash'),
            'card'           => trans('pos::pos.card'),
            'receipt'        => trans('pos::pos.receipt'),
            'new_order'      => trans('general.title.new', ['type' => trans_choice('pos::general.orders', 1)]),
            'print_receipt'  => trans('general.print') . ' ' . trans('pos::pos.receipt'),
            'send'           => trans('general.send'),
            'search_items'   => trans('general.search') . ' ' . trans_choice('general.items', 2),
        ];

        return $this->response('pos::index', compact('currency', 'translations'));
    }
}
