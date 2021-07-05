<?php

namespace Modules\CreditDebitNotes\Observers;

use App\Abstracts\Observer;
use App\Models\Banking\Transaction as Model;

class Transaction extends Observer
{
    /**
     * Listen to the retrieved event.
     *
     * @param  Model  $transaction
     * @return void
     */
    public function retrieved(Model $transaction)
    {
        if (!request()->route() || !request()->route()->named('transactions.index')) {
            return;
        }

        switch ($transaction->type) {
            case 'credit_note_refund':
                $transaction->setAttribute('type_title', trans('credit-debit-notes::credit_notes.refund_to_customer'));
                $transaction->setAttribute('route_name', 'credit-debit-notes.credit-notes.show');
                break;
            case 'debit_note_refund':
                $transaction->setAttribute('type_title', trans('credit-debit-notes::debit_notes.refund_from_vendor'));
                $transaction->setAttribute('route_name', 'credit-debit-notes.debit-notes.show');
                break;
        }
    }
}
