<?php

namespace Modules\CreditDebitNotes\Jobs\DebitNote;

use App\Abstracts\Job;
use Modules\CreditDebitNotes\Models\DebitNote;

class UpdateDebitNoteDetails extends Job
{
    protected $debit_note;

    protected $request;

    public function __construct(DebitNote $debit_note, $request)
    {
        $this->debit_note = $debit_note;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle()
    {
        return $this->debit_note->details()->update([
            'bill_id' => $this->request['bill_id'],
        ]);
    }
}
