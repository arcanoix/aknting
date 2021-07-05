<?php

namespace Modules\CreditDebitNotes\Jobs\DebitNote;

use App\Abstracts\Job;
use App\Models\Document\Document;
use Modules\CreditDebitNotes\Models\DebitNoteDetails;

class CreateDebitNoteDetails extends Job
{
    protected $debit_note;

    protected $request;

    public function __construct(Document $debit_note, $request)
    {
        $this->debit_note = $debit_note;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): ?DebitNoteDetails
    {
        if (!$this->request->has('bill_id')) {
            return null;
        }

        return DebitNoteDetails::create([
            'company_id' => $this->debit_note->company_id,
            'document_id' => $this->debit_note->id,
            'bill_id'    => $this->request['bill_id'],
        ]);
    }
}
