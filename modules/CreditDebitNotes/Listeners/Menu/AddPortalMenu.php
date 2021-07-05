<?php

namespace Modules\CreditDebitNotes\Listeners\Menu;

use App\Events\Menu\PortalCreated;

class AddPortalMenu
{
    /**
     * Handle the event.
     *
     * @param PortalCreated $event
     * @return void
     */
    public function handle(PortalCreated $event)
    {
        // Credit Notes
        if (user()->can('read-credit-debit-notes-portal-credit-notes')) {
            $event->menu->add([
                'url' => route('portal.credit-debit-notes.credit-notes.index'),
                'title' => trans_choice('credit-debit-notes::general.credit_notes', 2),
                'icon' => 'fas fa-file-invoice',
                'order' => 25,
            ]);
        }
    }
}
