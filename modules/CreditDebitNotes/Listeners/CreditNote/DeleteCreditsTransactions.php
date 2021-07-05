<?php

namespace Modules\CreditDebitNotes\Listeners\CreditNote;

use App\Events\Document\DocumentCancelled as Event;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Jobs\CreditNote\DeleteCreditsTransactions as DeleteTransactions;

class DeleteCreditsTransactions
{
    use Jobs;

    public function handle(Event $event)
    {
        if ($event->document->type !== CreditNote::TYPE) {
            return;
        }

        $this->dispatch(new DeleteTransactions($event->document));
    }
}
