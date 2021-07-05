<?php

namespace Modules\CreditDebitNotes\Http\Requests;

use App\Abstracts\Http\FormRequest;
use Date;

class RefundTransaction extends FormRequest
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
            'account_id' => 'required|integer',
            'paid_at' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|amount',
            'currency_code' => 'required|string|currency',
            'currency_rate' => 'required',
            'document_id' => 'required|integer',
            'contact_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'payment_method' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        if ($validator->errors()->count()) {
            $paid_at = Date::parse($this->request->get('paid_at'))->format('Y-m-d');

            $this->request->set('paid_at', $paid_at);
        }
    }
}
