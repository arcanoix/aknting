<?php

namespace Modules\CreditDebitNotes\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Document\Document;
use Illuminate\Support\Str;

class Metadata extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->prefillData();

        return view('credit-debit-notes::components.documents.form.metadata');
    }

    private function prefillData()
    {
        if (!empty($this->document)) {
            return;
        }

        $method = 'prefill' . Str::studly($this->type);
        $this->$method();
    }

    private function prefillCreditNote()
    {
        if (!$invoice_id = request()->query('invoice', null)) {
            return;
        }

        $invoice = Document::invoice()->findOrFail($invoice_id);

        $credit_note = new \stdClass();
        $credit_note->invoice_id = $invoice_id;
        $credit_note->customer_invoices = $invoice->contact->invoices()
            ->whereIn('status', ['sent', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        $this->contact = $invoice->contact;
        $this->document = $credit_note;
    }

    private function prefillDebitNote()
    {
        if (!$bill_id = request()->query('bill', null)) {
            return;
        }

        $bill = Document::bill()->findOrFail($bill_id);

        $debit_note = new \stdClass();
        $debit_note->bill_id = $bill_id;
        $debit_note->vendor_bills = $bill->contact->bills()
            ->whereIn('status', ['received', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        $this->contact = $bill->contact;
        $this->document = $debit_note;
    }
}
