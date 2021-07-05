<?php

namespace Modules\CreditDebitNotes\Observers;

use App\Abstracts\Observer;
use App\Jobs\Document\CreateDocumentHistory;
use Modules\CreditDebitNotes\Models\CreditsTransaction as Model;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Services\Credits;

class CreditsTransaction extends Observer
{
    use Jobs;

    public function deleted(Model $credits_transaction)
    {
        if (empty($credits_transaction->document_id)) {
            return;
        }

        if ($credits_transaction->type == 'expense') {
            $this->updateInvoice($credits_transaction);
        } else {
            $this->createCreditNoteHistory($credits_transaction);
        }
    }

    protected function updateInvoice($credits_transaction)
    {
        $invoice = $credits_transaction->invoice;

        $credits_transactions_count = (new Credits())->getTransactionsCount($invoice->id);
        $invoice->status = (($invoice->transactions->count() + $credits_transactions_count) > 0) ? 'partial' : 'sent';

        $invoice->save();

        $this->dispatch(new CreateDocumentHistory($invoice, 0, $this->getDescription($credits_transaction)));
    }

    protected function getDescription($credits_transaction)
    {
        $amount = money((double) $credits_transaction->amount, (string) $credits_transaction->currency_code, true)->format();

        return trans('messages.success.deleted', ['type' => $amount . ' ' . trans_choice('credit-debit-notes::invoices.credits', 1)]);
    }

    protected function createCreditNoteHistory($credits_transaction)
    {
        $amount = money((double) $credits_transaction->amount, (string) $credits_transaction->currency_code, true)->format();
        $history_desc =  trans('credit-debit-notes::credit_notes.credit_cancelled', ['amount' => $amount]);

        $this->dispatch(new CreateDocumentHistory($credits_transaction->credit_note, 0, $history_desc));
    }
}
