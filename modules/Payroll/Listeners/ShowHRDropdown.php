<?php

namespace Modules\Payroll\Listeners;

use Modules\Employees\Events\AddingHRDropdown;

class ShowHRDropdown
{
    public function handle(AddingHRDropdown $event)
    {
        if (user()->can([
            'read-payroll-payroll',
            'read-payroll-pay-calendars',
            'read-payroll-run-payrolls',
        ])) {
            $event->show_dropdown = true;

            return false;
        }
    }
}
