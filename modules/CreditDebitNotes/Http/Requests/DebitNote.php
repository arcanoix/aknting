<?php

namespace Modules\CreditDebitNotes\Http\Requests;

use App\Http\Requests\Document\Document;

class DebitNote extends Document
{
    public function rules(): array
    {
        $rules = parent::rules();

        $rules['issued_at'] = str_replace('|before_or_equal:due_at', '', $rules['issued_at']);
        $rules['due_at'] = str_replace('|after_or_equal:issued_at', '', $rules['due_at']);

        return $rules;
    }
}
