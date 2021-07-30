<?php

namespace Modules\CreditDebitNotes\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\EmailTemplate;

class Version130 extends Listener
{
    const ALIAS = 'credit-debit-notes';

    const VERSION = '1.3.0';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->deleteRedundantEmailTemplate();
    }

    public function deleteRedundantEmailTemplate()
    {
        EmailTemplate::where('alias', 'debit_note_new_customer')
            ->delete();
    }
}
