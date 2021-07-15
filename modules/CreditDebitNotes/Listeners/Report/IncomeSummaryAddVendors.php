<?php

namespace Modules\CreditDebitNotes\Listeners\Report;

use App\Events\Report\FilterShowing as Event;
use App\Models\Common\Contact;
use App\Reports\IncomeSummary;

class IncomeSummaryAddVendors
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

        if ($report->getSetting('group') != 'category') {
            return;
        }

        $vendors = Contact::select(['contacts.name', 'contacts.id'])
            ->enabled()
            ->vendor()
            ->join('transactions', function ($join) use ($report) {
                $join->on('contacts.id', '=', 'transactions.contact_id')
                    ->where('transactions.type', 'debit_note_refund');
            })
            ->pluck('name', 'id');

        $report->filters['customers'] = $vendors->merge($report->filters['customers'])->sort()->all();
    }
}
