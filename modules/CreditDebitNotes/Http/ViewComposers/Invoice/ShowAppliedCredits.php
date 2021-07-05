<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Invoice;

use Illuminate\View\View;
use Modules\CreditDebitNotes\Services\Credits;

class ShowAppliedCredits
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

        if (!$applied_credits) {
            return;
        }

        $total = $invoice->totals_sorted->first(function ($total) {
            return $total->code === 'total';
        });
        $total->amount -= $applied_credits;
        $view->with('invoice', $invoice);

        if (!$documentTemplate = config('type.invoice.template', false)) {
            $documentTemplate = setting('invoice.template') ?: 'default';
        }

        switch ($documentTemplate) {
            case 'modern':
                $viewName = 'credit-debit-notes::partials.invoice.applied_credits_modern';
                break;
            case 'default':
                $viewName = 'credit-debit-notes::partials.invoice.applied_credits_default';
                break;
            case 'classic':
                $viewName = 'credit-debit-notes::partials.invoice.applied_credits_classic';
                break;
        }
        $view->getFactory()->startPush(
            'grand_total_tr_start',
            view($viewName, ['invoice' => $invoice, 'applied_credits' => $applied_credits])
        );
    }
}
