<?php

namespace Modules\CreditDebitNotes\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Database\Eloquent\Builder;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class Version1111 extends Listener
{
    const ALIAS = 'credit-debit-notes';

    const VERSION = '1.1.11';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->deleteOrphanCreditsTransactions();
    }

    public function deleteOrphanCreditsTransactions()
    {
        CreditsTransaction::whereDoesntHave('credit_note')
            ->orWhereHas('credit_note', function (Builder $query) {
                $query->whereIn('status', ['draft', 'cancelled']);
            })
            ->delete();
    }
}
