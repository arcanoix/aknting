<?php

namespace Modules\Payroll\Listeners;

use App\Events\Module\SettingShowing as Event;

class ShowInSettingsPage
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->modules->settings['payroll'] = [
            'name' => trans('payroll::general.name'),
            'description' => trans('payroll::general.description'),
            'url' => route('payroll.settings.edit'),
            'icon' => 'fa fa-users',
        ];
    }
}
