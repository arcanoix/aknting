<?php

namespace Modules\CreditDebitNotes\Http\Requests;

use App\Abstracts\Http\FormRequest;

class CreditsTransaction extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string',
            'amount' => 'required|amount',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'document_id' => 'required|integer',
            'contact_id' => 'nullable|integer',
        ];
    }
}
