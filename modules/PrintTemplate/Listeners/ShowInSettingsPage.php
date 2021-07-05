<?php

namespace Modules\PrintTemplate\Listeners;

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
        $event->modules->settings['print-template'] = [
            'name' => trans('print-template::general.title'),
            'description' => trans('print-template::general.title'),
            'url' => route('print-template.settings.index'),
            'icon' => 'fa fa-angle-double-right',
        ];

        
    }
}
