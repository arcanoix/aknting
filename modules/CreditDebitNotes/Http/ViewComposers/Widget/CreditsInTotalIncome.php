<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Widget;

use App\Models\Document\Document;
use App\Utilities\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;

class CreditsInTotalIncome extends Widget
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
        $credits_current = $credits_open = $credits_overdue = 0;

        $credit_notes = CreditNote::whereHas('details', function (Builder $query) {
            $query->whereNull('invoice_id')
                ->where('credit_customer_account', true);
        });

        $this->applyFilters($credit_notes, ['date_field' => 'created_at'])->each(function ($credit_note) use (&$credits_current) {
            foreach ($credit_note->credits_transactions as $transaction) {
                $credits_current += $transaction->getAmountConvertedToDefault();
            }
        });

        $this->applyFilters(Document::invoice()->with('credit_notes.credits_transactions')->accrued()->notPaid(), ['date_field' => 'created_at'])->each(function ($invoice) use ($today, &$credits_open, &$credits_overdue) {
            foreach ($invoice->credit_notes as $credit_note) {
                foreach ($credit_note->credits_transactions as $transaction) {
                    if ($invoice->due_at > $today) {
                        $credits_open += $transaction->getAmountConvertedToDefault();
                    } else {
                        $credits_overdue += $transaction->getAmountConvertedToDefault();
                    }
                }
            }
        });

        $totals['grand'] = $totals['grand']->subtract(money($credits_current + $credits_open + $credits_overdue, setting('default.currency'), true));
        $totals['open'] = $totals['open']->subtract(money($credits_open , setting('default.currency'), true));
        $totals['overdue'] = $totals['overdue']->subtract(money($credits_overdue, setting('default.currency'), true));

        $view->with(['totals' => $totals]);
    }
}
