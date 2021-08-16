<?php

namespace Modules\CreditDebitNotes\Listeners\Menu;

use App\Events\Menu\ItemAuthorizing as Event;

class AuthorizeDropdownMenu
{
    public function handle(Event $event)
    {
        $sales = trim(trans_choice('general.sales', 2));
        $purchases = trim(trans_choice('general.purchases', 2));

        if (!in_array($event->item->title, [$sales, $purchases])) {
            return;
        }

        // Sales -> Credit Notes
        if ($event->item->title === $sales) {
            $event->item->permissions[] = 'read-credit-debit-notes-credit-notes';
        }

        // Purchases -> Debit Notes
        if ($event->item->title === $purchases) {
            $event->item->permissions[] = 'read-credit-debit-notes-debit-notes';
        }
    }
}
