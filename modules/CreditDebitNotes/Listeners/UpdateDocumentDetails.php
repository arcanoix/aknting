<?php

namespace Modules\CreditDebitNotes\Listeners;

use App\Events\Document\DocumentUpdated as Event;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Jobs\CreditNote\UpdateCreditNoteDetails;
use Modules\CreditDebitNotes\Jobs\DebitNote\UpdateDebitNoteDetails;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\DebitNote;

class UpdateDocumentDetails
{
    use Jobs;

    public function handle(Event $event)
    {
        if (!in_array($event->document->type, [CreditNote::TYPE, DebitNote::TYPE], true)) {
            return;
        }

        $jobs = [
            CreditNote::TYPE => UpdateCreditNoteDetails::class,
            DebitNote::TYPE  => UpdateDebitNoteDetails::class,
        ];

        $this->dispatch(new $jobs[$event->document->type]($event->document, $event->request));
    }
}
