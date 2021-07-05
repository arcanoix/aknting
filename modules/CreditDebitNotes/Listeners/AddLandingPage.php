<?php

namespace Modules\CreditDebitNotes\Listeners;

use App\Events\Auth\LandingPageShowing;

class AddLandingPage
{
    /**
     * Handle the event.
     *
     * @param LandingPageShowing $event
     * @return void
     */
    public function handle(LandingPageShowing $event)
    {
        $event->user->landing_pages['credit-debit-notes.credit-notes.index'] = trans_choice('credit-debit-notes::general.credit_notes', 2);
        $event->user->landing_pages['credit-debit-notes.debit-notes.index'] = trans_choice('credit-debit-notes::general.debit_notes', 2);
    }
}
