<?php

namespace Modules\CreditDebitNotes\Jobs\CreditNote;

use App\Abstracts\Job;
use App\Models\Document\Document;

class DeleteCreditsTransactions extends Job
{
    protected $credit_note;

    public function __construct(Document $credit_note)
    {
        $this->credit_note = $credit_note;
    }

    public function handle(): bool
    {
        $this->credit_note->credits_transactions()->delete();

        return true;
    }
}
