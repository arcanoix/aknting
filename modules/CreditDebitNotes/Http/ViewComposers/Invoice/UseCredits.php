<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Invoice;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Services\Credits;

class UseCredits
{
    /**
     * @var Credits
     */
    private $credits;

    public function __construct(Credits $credits)
    {
        $this->credits = $credits;
    }

    public function compose(View $view)
    {
        $invoice = $view->getData()['invoice'];
        $applied_credits = $this->credits->getAppliedCredits($invoice);
        $available_credits = $this->credits->getAvailableCredits($invoice->contact_id);

        if((empty($invoice->paid) || ($invoice->paid + $applied_credits != $invoice->amount)) && $available_credits) {
            $view->getFactory()->startPush(
                'timeline_get_paid_body_button_payment_end',
                view('credit-debit-notes::partials.invoice.use_credits_button')
            );
        }

        $view->getFactory()->startPush(
            'scripts_end',
            view('credit-debit-notes::partials.invoice.script')
        );

        $view->getFactory()->startPush(
            'body_end',
            '<div id="credit-debit-notes-vue-entrypoint"><component v-bind:is="component"></component></div>'
        );
    }
}
