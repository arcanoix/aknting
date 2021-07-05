<?php

namespace Modules\CreditDebitNotes\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class Version105 extends Listener
{
    const ALIAS = 'credit-debit-notes';

    const VERSION = '1.0.5';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->updateCreditTransactions();
    }

    public function updateDatabase()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);
    }

    public function updateCreditTransactions()
    {
        $credits_transactions = CreditsTransaction::with(['invoice', 'credit_note'])->cursor();

        foreach ($credits_transactions as $transaction) {
            switch ($transaction->type) {
                case 'income':
                    $category_id = $transaction->credit_note->category_id;
                    $paid_at = $transaction->credit_note->issued_at;
                    break;

                case 'expense':
                    $category_id = $transaction->invoice->category_id;
                    $paid_at = $transaction->created_at;
                    break;

                default:
                    $category_id = null;
                    $paid_at = $transaction->created_at;
            }

            DB::table('credits_transactions')
                ->where('id', $transaction->id)
                ->update([
                    'category_id' => $category_id,
                    'paid_at' => $paid_at,
                ]);
        }
    }
}
