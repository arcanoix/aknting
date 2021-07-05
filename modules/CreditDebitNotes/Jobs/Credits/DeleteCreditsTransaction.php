<?php

namespace Modules\CreditDebitNotes\Jobs\Credits;

use App\Abstracts\Job;
use Exception;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class DeleteCreditsTransaction extends Job
{
    protected $credits_transaction;

    /**
     * Create a new job instance.
     *
     * @param  CreditsTransaction  $credits_transaction
     */
    public function __construct(CreditsTransaction $credits_transaction)
    {
        $this->credits_transaction = $credits_transaction;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     * @throws Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->credits_transaction->delete();
        });

        return true;
    }
}
