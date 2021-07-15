<?php

namespace Modules\Pos\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Settings extends FormRequest
{
    public function rules(): array
    {
        return [
            'number_prefix'       => 'required|string',
            'number_digit'        => 'required|numeric',
            'number_next'         => 'required|numeric',
            'cash_account_id'     => 'required',
            'cash_payment_method' => 'required',
            'card_account_id'     => 'required',
            'card_payment_method' => 'required',
            'guest_customer_id'   => 'required',
            'sale_category_id'    => 'required',
            'change_category_id'  => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
