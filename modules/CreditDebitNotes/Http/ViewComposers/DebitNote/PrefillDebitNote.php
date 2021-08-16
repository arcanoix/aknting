<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\DebitNote;

use App\Models\Document\Document;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\DebitNote;

class PrefillDebitNote
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view_data = $view->getData();

        if ($view_data['type'] !== DebitNote::TYPE) {
            return;
        }

        if (!empty($view_data['document'])) {
            return;
        }

        if (!$bill_id = request()->query('bill', null)) {
            return;
        }

        $bill = Document::bill()->findOrFail($bill_id);

        $document = new \stdClass();
        $document->bill_id = $bill_id;
        $document->category_id = $bill->category_id;
        $document->vendor_bills = $bill->contact->bills()
            ->whereIn('status', ['received', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        $contact = $bill->contact;

        $view->with(compact('document', 'contact'));
    }
}
