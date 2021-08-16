<?php

namespace Modules\CreditDebitNotes\Listeners\Menu;

use App\Events\Menu\AdminCreated;

class AddAdminMenu
{
    /**
     * Handle the event.
     *
     * @param AdminCreated $event
     * @return void
     */
    public function handle(AdminCreated $event)
    {
        $user = user();
        $menu = $event->menu;

        // Sales -> Credit Notes
        if ($user->can('read-credit-debit-notes-credit-notes')) {
            $item = $menu->whereTitle(trim(trans_choice('general.sales', 2)));
            $item->url(
                route('credit-debit-notes.credit-notes.index'),
                trans_choice('credit-debit-notes::general.credit_notes', 2),
                25,
                ['icon' => '']
            );
        }

        // Purchases -> Debit Notes
        if ($user->can('read-credit-debit-notes-debit-notes')) {
            $item = $menu->whereTitle(trim(trans_choice('general.purchases', 2)));
            $item->url(
                route('credit-debit-notes.debit-notes.index'),
                trans_choice('credit-debit-notes::general.debit_notes', 2),
                25,
                ['icon' => '']
            );
        }
    }
}
