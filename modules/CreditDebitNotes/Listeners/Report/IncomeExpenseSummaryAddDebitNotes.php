<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\DataLoaded as Event;
use App\Reports\IncomeExpenseSummary;
use Modules\CreditDebitNotes\Models\DebitNote;

class IncomeExpenseSummaryAddDebitNotes
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

        if (!$report instanceof IncomeExpenseSummary) {
            return;
        }

        if ($report->getSetting('basis') == 'cash') {
            return;
        }

        if ($report->getSetting('group') != 'category') {
            return;
        }

        // Debit notes
        $debit_notes = $report->applyFilters(DebitNote::accrued(), ['date_field' => 'issued_at'])->get();

        $report->setTotals($debit_notes, 'issued_at');
    }
}
