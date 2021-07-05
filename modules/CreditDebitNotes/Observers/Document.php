<?php

namespace Modules\CreditDebitNotes\Observers;

use App\Abstracts\Observer;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Jobs\CreditNote\DeleteCreditsTransactions;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\DebitNote;

class Document extends Observer
{
    use Jobs;

    /**
     * @param DebitNote|CreditNote $document
     */
    public function deleted($document)
    {
        if (!in_array($document->type, [DebitNote::TYPE, CreditNote::TYPE], true)) {
            return;
        }

        $document->details()->delete();

        if ($document->type === CreditNote::TYPE) {
            $this->dispatch(new DeleteCreditsTransactions($document));

        }
    }
}
