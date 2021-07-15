<?php

namespace Modules\Pos\Listeners;

use App\Events\Menu\AdminCreated;

class AddToAdminMenu
{
    public function handle(AdminCreated $event)
    {
        if (user()->can('read-pos-main') || user()->can('read-pos-orders')) {

            $event->menu->dropdown(trans('pos::general.name'), function ($sub) {
                if (user()->can('read-pos-main')) {
                    $sub->route('pos.pos', trans('pos::general.name'), [], 10, ['target' => '_blank']);
                }

                if (user()->can('read-pos-orders')) {
                    $sub->route('pos.orders.index', trans_choice('pos::general.orders', 2), [], 20, []);
                }
            }, 51, ['icon' => 'fas fa-cash-register']);
        }
    }
}
