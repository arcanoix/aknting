<?php

namespace Modules\Payroll\Http\Requests\Common;

use App\Abstracts\Http\FormRequest as Request;

class Setting extends Request
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
        $rules = [
            'run_payroll_prefix' => 'required',
            'run_payroll_digit' => 'required',
            'run_payroll_next' => 'required',
            'account' => 'required|integer',
            'category' => 'required|integer',
            'payment_method' => 'required|string',
        ];

        return $rules;
    }
}
