<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\DataLoaded as Event;
use App\Models\Banking\Transaction;
use App\Reports\ExpenseSummary;

class ExpenseSummaryAddRefunds
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $report = $event->class;

        if (!$report instanceof ExpenseSummary) {
            return;
        }

        if ($report->getSetting('basis') == 'cash') {
            return;
        }

        if ($report->getSetting('group') != 'category') {
            return;
        }

        $transactions = $report->applyFilters(Transaction::with('recurring')->expense()->isNotTransfer(), ['date_field' => 'paid_at']);

        // Refunds
        $refunds = $transactions->type('credit_note_refund')->get();
        $report->setTotals($refunds, 'paid_at');
    }
}
