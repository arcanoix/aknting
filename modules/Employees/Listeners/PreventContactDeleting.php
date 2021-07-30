<?php

namespace Modules\Employees\Listeners;

use App\Events\Common\RelationshipCounting as Event;
use App\Models\Common\Contact;

class PreventContactDeleting
{
    public function handle(Event $event)
    {
        if (!$event->record->model instanceof Contact) {
            return;
        }

        $event->record->relationships['employee'] = 'employees::general.employees';
    }
}
