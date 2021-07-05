<?php

namespace Modules\CreditDebitNotes\Jobs\CreditNote;

use App\Abstracts\Job;
use App\Models\Document\Document;
use Modules\CreditDebitNotes\Models\CreditNoteDetails;

class CreateCreditNoteDetails extends Job
{
    protected $credit_note;

    protected $request;

    public function __construct(Document $credit_note, $request)
    {
        $this->credit_note = $credit_note;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): ?CreditNoteDetails
    {
        if (!count($this->request->all())) {
            return null;
        }

        return CreditNoteDetails::create([
            'company_id'              => $this->credit_note->company_id,
            'document_id'             => $this->credit_note->id,
            'invoice_id'              => $this->request['invoice_id'] ?? null,
            'credit_customer_account' => $this->request['credit_customer_account'] ?? false,
        ]);
    }
}
