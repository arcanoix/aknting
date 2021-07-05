<?php

namespace Modules\Payroll\Listeners;

use Modules\Employees\Events\DetermineRelationships as Event;

class AddEmployeeRelationship
{
    public function handle(Event $event)
    {
        $event->rel->relationships['run_payroll_employees'] = 'payroll::general.run_payrolls';
        $event->rel->relationships['pay_calendar_employees'] = 'payroll::general.pay_calendars';
    }
}
