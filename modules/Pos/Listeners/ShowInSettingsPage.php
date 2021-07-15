<?php

namespace Modules\Pos\Listeners;

use App\Events\Module\SettingShowing as Event;

class ShowInSettingsPage
{
    public function handle(Event $event)
    {
        $event->modules->settings['pos'] = [
            'name' => trans('pos::general.name'),
            'description' => trans('pos::general.description'),
            'url' => route('pos.settings.edit'),
            'icon' => 'fas fa-cash-register',
        ];
    }
}
