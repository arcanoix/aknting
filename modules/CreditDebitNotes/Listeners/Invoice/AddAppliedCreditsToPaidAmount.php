<?php

namespace Modules\CreditDebitNotes\Listeners\Invoice;

use App\Events\Document\PaidAmountCalculated as Event;
use App\Models\Sale\Invoice;
use Modules\CreditDebitNotes\Services\Credits;

class AddAppliedCreditsToPaidAmount
{
    /**
     * @var Credits
     */
    private $credits;

    public function __construct(Credits $credits)
    {
        $this->credits = $credits;
    }

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (!$event->model instanceof Invoice) {
            return;
        }

        $invoice = $event->model;

        $precision = config('money.' . $invoice->currency_code . '.precision');

        $applied_credits = $this->credits->getAppliedCredits($invoice);
        $invoice->paid_amount = round($invoice->paid_amount + $applied_credits, $precision);
    }
}
