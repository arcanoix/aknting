<?php

namespace Modules\CreditDebitNotes\Jobs\Refund;

use App\Abstracts\Job;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CreateDocumentHistory;
use App\Models\Banking\Transaction;
use App\Traits\Currencies;
use Date;
use Modules\CreditDebitNotes\Models\CreditNote;
use Throwable;

class CreateRefundTransaction extends Job
{
    use Currencies;

    protected $model;

    protected $request;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param  $model
     * @param  $request
     */
    public function __construct($model, $request = [])
    {
        $this->model = $model;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Transaction
     * @throws Throwable
     */
    public function handle()
    {
        $this->prepareRequest();

        \DB::transaction(function () {
            $this->transaction = $this->dispatch(new CreateTransaction($this->request));

            $this->createHistory();
        });

        return $this->transaction;
    }

    protected function prepareRequest()
    {
        $this->request['company_id'] = company_id();
        $this->request['type'] = ($this->model instanceof CreditNote) ? 'credit_note_refund' : 'debit_note_refund';
        $this->request['paid_at'] = isset($this->request['paid_at']) ? $this->request['paid_at'] : Date::now()->format('Y-m-d');
        $this->request['amount'] = isset($this->request['amount']) ? $this->request['amount'] : $this->model->amount;
        $this->request['currency_code'] = isset($this->request['currency_code']) ? $this->request['currency_code'] : $this->model->currency_code;
        $this->request['currency_rate'] = config('money.' . $this->request['currency_code'] . '.rate');
        $this->request['account_id'] = isset($this->request['account_id']) ? $this->request['account_id'] : setting('default.account');
        $this->request['document_id'] = isset($this->request['document_id']) ? $this->request['document_id'] : $this->model->id;
        $this->request['category_id'] = isset($this->request['category_id']) ? $this->request['category_id'] : $this->model->category_id;
        $this->request['contact_id'] = isset($this->request['contact_id']) ? $this->request['contact_id'] : $this->model->contact_id;
        $this->request['payment_method'] = isset($this->request['payment_method']) ? $this->request['payment_method'] : setting('default.payment_method');
        $this->request['notify'] = isset($this->request['notify']) ? $this->request['notify'] : 0;
    }

    protected function createHistory()
    {
        $amount = money((double) $this->transaction->amount, (string) $this->transaction->currency_code, true)->format();

        if ($this->model instanceof CreditNote) {
            $history_desc =  trans('credit-debit-notes::credit_notes.refunded_customer_with', ['customer' => $this->model->contact_name, 'amount' => $amount]);
        } else {
            $history_desc =  trans('credit-debit-notes::debit_notes.received_refund_from_vendor', ['vendor' => $this->model->contact_name, 'amount' => $amount]);
        }
        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }
}
