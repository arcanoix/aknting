<?php

namespace Modules\CreditDebitNotes\Listeners\Invoice;

use App\Events\Document\TransactionsCounted as Event;
use App\Models\Document\Document;
use Modules\CreditDebitNotes\Services\Credits;

class AddCreditsTransactionsCount
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
        if ($event->model->type !== Document::INVOICE_TYPE) {
            return;
        }

        $invoice = $event->model;

        $credits_transactions_count = $this->credits->getTransactionsCount($invoice->id);
        $invoice->transactions_count += $credits_transactions_count;
    }
}
