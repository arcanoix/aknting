<?php

namespace Modules\CreditDebitNotes\Observers;

use App\Abstracts\Observer;
use App\Models\Document\Document as Model;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class Invoice extends Observer
{
    public function deleted(Model $invoice)
    {
        if ($invoice->type !== Model::INVOICE_TYPE) {
            return;
        }

        CreditsTransaction::expense()->document($invoice->id)->delete();
    }
}
