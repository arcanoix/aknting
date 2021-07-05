<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Invoice;

use App\Models\Document\Document;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Services\Credits;

class ReduceAmountDue
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
        $document = $view->getData()['document'];

        if ($document->type !== Document::INVOICE_TYPE) {
            return;
        }

        $applied_credits = $this->credits->getAppliedCredits($document);
        $document->grand_total -= $applied_credits;

        $view->with('document', $document);
    }

}
