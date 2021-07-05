<?php

namespace Modules\Payroll\Http\Requests\Employee;

use App\Abstracts\Http\FormRequest as Request;

class Benefit extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'recurring' => 'required|string'
        ];
    }
}
