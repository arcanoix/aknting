<?php

namespace Modules\CreditDebitNotes\Listeners;

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
        $event->modules->settings['credit-debit-notes-credit-note'] = [
            'name' => trans_choice('credit-debit-notes::general.credit_notes', 1),
            'description' => trans('credit-debit-notes::settings.credit_note.description'),
            'url' => route('credit-debit-notes.settings.credit-note.edit'),
            'icon' => 'fas fa-file-invoice',
            'permission' => 'read-credit-debit-notes-settings-credit-note',
        ];
        $event->modules->settings['credit-debit-notes-debit-note'] = [
            'name' => trans_choice('credit-debit-notes::general.debit_notes', 1),
            'description' => trans('credit-debit-notes::settings.debit_note.description'),
            'url' => route('credit-debit-notes.settings.debit-note.edit'),
            'icon' => 'fas fa-file-invoice-dollar',
            'permission' => 'read-credit-debit-notes-settings-debit-note',
        ];
    }
}
