<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Widget;

use App\Models\Document\Document;
use App\Utilities\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\DebitNote;

class DebitNotesInTotalExpenses extends Widget
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $totals = $view->getData()['totals'];
        $today = Date::today()->toDateString();
        $debits_current = $debits_open = $debits_overdue = 0;

        $debit_notes = DebitNote::whereHas('details', function (Builder $query) {
            $query->whereNull('bill_id');
        })
            ->accrued();

        $this->applyFilters($debit_notes, ['date_field' => 'created_at'])->each(function ($debit_note) use (&$debits_current) {
            $debits_current += $debit_note->getAmountConvertedToDefault();
        });

        $this->applyFilters(Document::bill()->with('debit_notes')->accrued()->notPaid(), ['date_field' => 'created_at'])->each(function ($bill) use ($today, &$debits_open, &$debits_overdue) {
            $debit_notes = $bill->debit_notes->where('status', 'sent');
            foreach ($debit_notes as $debit_note) {
                if ($bill->due_at > $today) {
                    $debits_open += $debit_note->getAmountConvertedToDefault();
                } else {
                    $debits_overdue += $debit_note->getAmountConvertedToDefault();
                }
            }
        });

        $totals['grand'] = $totals['grand']->subtract(money($debits_current + $debits_open + $debits_overdue, setting('default.currency'), true));
        $totals['open'] = $totals['open']->subtract(money($debits_open , setting('default.currency'), true));
        $totals['overdue'] = $totals['overdue']->subtract(money($debits_overdue, setting('default.currency'), true));

        $view->with(['totals' => $totals]);
    }
}
