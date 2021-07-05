<?php

namespace Modules\CreditDebitNotes\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\DB;

class Version115 extends Listener
{
    const ALIAS = 'credit-debit-notes';

    const VERSION = '1.1.5';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateUserLandingPage();
    }

    public function updateUserLandingPage()
    {
        $old_landing_pages = [
            'credit-notes.index',
            'debit-notes.index',
        ];

        foreach ($old_landing_pages as $landing_page) {
            DB::table('users')
                ->where('landing_page', $landing_page)
                ->update([
                    'landing_page' => 'credit-debit-notes.' . $landing_page,
                ]);
        }
    }
}
