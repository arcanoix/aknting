<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Invoice;

use App\Traits\DateTime;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Services\Credits;

class ShowCreditsTransactions
{
    use DateTime;

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
        $transactions = $this->credits->getTransactions($invoice->id);

        $view->getFactory()->startPush(
            'row_footer_transactions_end',
            view(
                'credit-debit-notes::partials.invoice.credits_transactions',
                [
                    'invoice' => $invoice,
                    'date_format' => $this->getCompanyDateFormat(),
                    'transactions' => $transactions
                ]
            )
        );
    }
}
