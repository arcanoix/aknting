<?php

namespace Modules\CreditDebitNotes\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use Illuminate\Support\Str;

class Advanced extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $category_type = $this->categoryType;

        if ($category_type) {
            $categories = Category::$category_type()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
        } else {
            $categories = Category::enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
        }

        $recurring_class = 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
        $more_class = 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
        $more_form_class = 'col-md-12';

        if ($this->hideRecurring && (!$this->hideCategory || !$this->hideAttachment)) {
            $more_class = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
            $more_form_class = 'col-md-6';
        } else if ($this->hideRecurring && ($this->hideCategory && $this->hideAttachment)) {
            $recurring_class = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
        }

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        $this->prefillData();

        return view('components.documents.form.advanced', compact('categories', 'category_type', 'recurring_class', 'more_class', 'more_form_class', 'file_types'));
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
        $credit_note->category_id = $invoice->category_id;

        $this->document = $credit_note;
    }

    private function prefillDebitNote()
    {
        if (!$bill_id = request()->query('bill', null)) {
            return;
        }

        $bill = Document::bill()->findOrFail($bill_id);

        $debit_note = new \stdClass();
        $debit_note->category_id = $bill->category_id;

        $this->document = $debit_note;
    }
}
