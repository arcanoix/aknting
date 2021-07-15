<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\FilterShowing as Event;
use App\Models\Common\Contact;
use App\Reports\ExpenseSummary;

class ExpenseSummaryAddCustomers
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

        if ($report->getSetting('group') != 'category') {
            return;
        }

        $customers = Contact::select(['contacts.name', 'contacts.id'])
            ->enabled()
            ->customer()
            ->join('transactions', function ($join) use ($report) {
                $join->on('contacts.id', '=', 'transactions.contact_id')
                    ->where('transactions.type', 'credit_note_refund');
            })
            ->pluck('name', 'id');

        $report->filters['vendors'] = $customers->merge($report->filters['vendors'])->sort()->all();
    }
}
