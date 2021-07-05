<?php

namespace Modules\CreditDebitNotes\Jobs\CreditNote;

use App\Abstracts\Job;
use Modules\CreditDebitNotes\Models\CreditNote;

class UpdateCreditNoteDetails extends Job
{
    protected $credit_note;

    protected $request;

    public function __construct(CreditNote $credit_note, $request)
    {
        $this->credit_note = $credit_note;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle()
    {
        return $this->credit_note->details()->updateOrCreate(
            [
                'company_id' => $this->credit_note->company_id,
            ],
            [
                'invoice_id'              => $this->request['invoice_id'],
                'credit_customer_account' => $this->request['credit_customer_account'],
            ]
        );
    }
}
