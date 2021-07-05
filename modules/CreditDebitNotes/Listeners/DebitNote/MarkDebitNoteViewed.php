<?php

namespace Modules\CreditDebitNotes\Listeners\DebitNote;

use App\Jobs\Document\CreateDocumentHistory;
use Modules\CreditDebitNotes\Events\DebitNoteViewed as Event;
use App\Traits\Jobs;

class MarkDebitNoteViewed
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $debit_note = $event->debit_note;

        if ($debit_note->status != 'sent') {
            return;
        }

        $debit_note->status = 'viewed';
        $debit_note->save();

        $this->dispatch(new CreateDocumentHistory($event->debit_note, 0, trans('credit-debit-notes::debit_notes.messages.marked_viewed')));
    }
}
