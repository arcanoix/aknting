<?php

namespace Modules\CreditDebitNotes\Listeners;

use App\Events\Document\DocumentCreated as Event;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Jobs\CreditNote\CreateCreditNoteDetails;
use Modules\CreditDebitNotes\Jobs\DebitNote\CreateDebitNoteDetails;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\DebitNote;

class CreateDocumentDetails
{
    use Jobs;

    public function handle(Event $event)
    {
        if (!in_array($event->document->type, [CreditNote::TYPE, DebitNote::TYPE], true)) {
            return;
        }

        $jobs = [
            CreditNote::TYPE => CreateCreditNoteDetails::class,
            DebitNote::TYPE  => CreateDebitNoteDetails::class,
        ];

        $this->dispatch(new $jobs[$event->document->type]($event->document, $event->request));
    }
}
