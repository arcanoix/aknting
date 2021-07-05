<?php

namespace Modules\CreditDebitNotes\Jobs\Credits;

use App\Abstracts\Job;
use App\Jobs\Document\CreateDocumentHistory;
use App\Models\Document\Document;
use App\Traits\Currencies;
use Date;
use Exception;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\CreditsTransaction;
use Modules\CreditDebitNotes\Services\Credits;

class CreateCreditsTransaction extends Job
{
    use Currencies;

    protected $model;

    protected $request;

    protected $transaction;

    public function __construct($model, $request = [])
    {
        $this->model = $model;
        $this->request = $this->getRequestInstance($request);
    }

    public function handle(): CreditsTransaction
    {
        $this->prepareRequest();

        if ($this->model->type === Document::INVOICE_TYPE) {
            $this->checkAmount();
        }

        \DB::transaction(function () {
            $this->transaction = CreditsTransaction::create($this->request->all());

            $this->model->save();

            if ($this->model->type === Document::INVOICE_TYPE) {
                $this->createInvoiceHistory();
            } else {
                $this->createCreditNoteHistory();
            }
        });

        return $this->transaction;
    }

    protected function prepareRequest()
    {
        if (!isset($this->request['amount'])) {
            $this->request['amount'] = $this->model->amount;

            if ($this->model->type === Document::INVOICE_TYPE) {
                $this->request['amount'] -= $this->model->paid;
            }
        }

        if (!isset($this->request['paid_at'])) {
            $this->request['paid_at'] = ($this->model->type === CreditNote::TYPE) ? $this->model->issued_at : Date::now()->format('Y-m-d');
        }

        $this->request['company_id'] = company_id();
        $this->request['type'] = ($this->model->type === Document::INVOICE_TYPE) ? 'expense' : 'income';
        $this->request['currency_code'] = isset($this->request['currency_code']) ? $this->request['currency_code'] : $this->model->currency_code;
        $this->request['currency_rate'] = config('money.' . $this->request['currency_code'] . '.rate');
        $this->request['document_id'] = isset($this->request['document_id']) ? $this->request['document_id'] : $this->model->id;
        $this->request['contact_id'] = isset($this->request['contact_id']) ? $this->request['contact_id'] : $this->model->contact_id;
        $this->request['category_id'] = isset($this->request['category_id']) ? $this->request['category_id'] : $this->model->category_id;
        $this->request['notify'] = isset($this->request['notify']) ? $this->request['notify'] : 0;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function checkAmount()
    {
        $code = $this->request['currency_code'];
        $rate = $this->request['currency_rate'];
        $precision = config('money.' . $code . '.precision');

        $amount = $this->request['amount'] = round($this->request['amount'], $precision);

        if ($this->model->currency_code != $code) {
            $converted_amount = $this->convertBetween($amount, $code, $rate, $this->model->currency_code, $this->model->currency_rate);

            $amount = round($converted_amount, $precision);
        }

        $credits = new Credits();
        $available_credits = $credits->getAvailableCredits($this->model->contact_id);
        if ($amount > $available_credits) {
            $message = trans('credit-debit-notes::invoices.messages.error.not_enough_credits', ['credits' => money($available_credits, $code, true)]);

            throw new Exception($message);
        }

        $applied_credits = $credits->getAppliedCredits($this->model);

        $total_amount = round($this->model->amount - $this->model->paid - $applied_credits, $precision);
        unset($this->model->reconciled);

        $compare = bccomp($amount, $total_amount, $precision);

        if ($compare === 1) {
            $error_amount = $total_amount;

            if ($this->model->currency_code != $code) {
                $converted_amount = $this->convertBetween($total_amount, $this->model->currency_code, $this->model->currency_rate, $code, $rate);

                $error_amount = round($converted_amount, $precision);
            }

            $message = trans('credit-debit-notes::invoices.messages.error.over_payment', ['amount' => money($error_amount, $code, true)]);

            throw new Exception($message);
        } else {
            $this->model->status = ($compare === 0) ? 'paid' : 'partial';
        }

        return true;
    }

    protected function createInvoiceHistory()
    {
        $history_desc = money((double) $this->transaction->amount, (string) $this->transaction->currency_code, true)->format() . ' ' . trans_choice('credit-debit-notes::invoices.credits', 1);

        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }

    protected function createCreditNoteHistory()
    {
        $amount = money((double) $this->transaction->amount, (string) $this->transaction->currency_code, true)->format();
        $history_desc =  trans('credit-debit-notes::credit_notes.customer_credited_with', ['customer' => $this->model->contact_name, 'amount' => $amount]);

        $this->dispatch(new CreateDocumentHistory($this->model, 0, $history_desc));
    }
}
