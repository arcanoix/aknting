<?php

namespace Modules\Payroll\Listeners;

use Auth;
use Modules\Employees\Events\HRDropdownCreated as Event;

class AddToAdminMenu
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = Auth::user();

        if (!$user->can([
            'read-payroll-payroll',
            'read-payroll-pay-calendars',
            'read-payroll-run-payrolls',
        ])) {
            return;
        }

        $event->menu->dropdown(trans('payroll::general.name'), function ($sub) use ($user) {
            if ($user->can('read-payroll-pay-calendars')) {
                $sub->route('payroll.pay-calendars.index', trans_choice('payroll::general.pay_calendars', 2), [], 10, []);
            }

            if ($user->can('read-payroll-run-payrolls')) {
                $sub->route('payroll.run-payrolls.index', trans_choice('payroll::general.run_payrolls', 2), [], 20, []);
            }
        }, 30, ['title' => trans('payroll::general.name')]);
    }
}
