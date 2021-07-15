<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\DataLoaded as Event;
use App\Reports\IncomeSummary;
use Modules\CreditDebitNotes\Models\CreditsTransaction;

class IncomeSummarySubtractCredits
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

        if (!$report instanceof IncomeSummary) {
            return;
        }

        if ($report->getSetting('basis') == 'cash') {
            return;
        }

        if ($report->getSetting('group') != 'category') {
            return;
        }

        // Credits
        $credits_transactions = $report->applyFilters(CreditsTransaction::income(), ['date_field' => 'paid_at']);
        $credits = $credits_transactions->get()
            ->each(function ($credit_transaction) {
                $credit_transaction->amount = -$credit_transaction->amount;
            });
        $report->setTotals($credits, 'paid_at');
    }
}
