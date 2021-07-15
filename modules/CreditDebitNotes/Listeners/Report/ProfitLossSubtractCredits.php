<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\DataLoaded as Event;
use App\Reports\ProfitLoss;
use Illuminate\Database\Eloquent\Builder;
use Modules\CreditDebitNotes\Models\CreditNote;

class ProfitLossSubtractCredits
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

        if (!$report instanceof ProfitLoss) {
            return;
        }

        if ($report->getSetting('basis') == 'cash') {
            return;
        }

        if ($report->getSetting('group') != 'category') {
            return;
        }

        // Credit notes
        $credit_notes = CreditNote::whereHas('details', function (Builder $query) {
            $query->where('credit_customer_account', true);
        })
            ->with('totals')
            ->accrued();

        $credit_notes = $report->applyFilters($credit_notes, ['date_field' => 'issued_at'])->get();
        $report->setTotals($credit_notes, 'issued_at', true, $report->tables['income'], false);
    }
}
