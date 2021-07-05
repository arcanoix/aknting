<?php

namespace Modules\CreditDebitNotes\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;

class Main extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('credit-debit-notes::components.documents.form.main');
    }
}
