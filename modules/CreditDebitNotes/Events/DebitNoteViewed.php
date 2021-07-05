<?php

namespace Modules\CreditDebitNotes\Events;

use Illuminate\Queue\SerializesModels;

class DebitNoteViewed
{
    use SerializesModels;

    public $debit_note;

    /**
     * Create a new event instance.
     *
     * @param $debit_note
     */
    public function __construct($debit_note)
    {
        $this->debit_note = $debit_note;
    }
}
