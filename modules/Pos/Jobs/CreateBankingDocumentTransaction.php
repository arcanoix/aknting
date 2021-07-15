<?php

namespace Modules\Pos\Jobs;

use App\Jobs\Banking\CreateBankingDocumentTransaction as Job;
use App\Jobs\Document\CreateDocumentHistory;

class CreateBankingDocumentTransaction extends Job
{
    protected function checkAmount(): bool
    {
        return true;
    }

    protected function createHistory()
    {
        if ($this->transaction->type === 'income') {
            parent::createHistory();

            return;
        }

        $history_desc = money((double) $this->transaction->amount, (string) $this->transaction->currency_code, true)->format() . ' ' . trans('pos::orders.change');

        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }
}
