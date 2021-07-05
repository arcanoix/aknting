<?php

namespace Modules\CreditDebitNotes\Http\Requests\Portal;

use App\Abstracts\Http\FormRequest;

class CreditNoteShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->guest()) {
            return true;
        }

        return $this->credit_note->contact_id == user()->contact->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
