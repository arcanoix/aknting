<?php

namespace Modules\CreditDebitNotes\Listeners\CreditNote;

use App\Events\Document\DocumentSent as Event;
use App\Traits\Jobs;
use Modules\CreditDebitNotes\Jobs\Credits\CreateCreditsTransaction;
use Modules\CreditDebitNotes\Models\CreditNote;

class CreditCustomerAccount
{
    use Jobs;

    public function handle(Event $event)
    {
        if ($event->document->type !== CreditNote::TYPE) {
            return;
        }

        if ($event->document->credit_customer_account) {
            $this->dispatch(new CreateCreditsTransaction($event->document));
        }
    }
}
