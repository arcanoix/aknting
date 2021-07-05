<?php

namespace Modules\Payroll\Http\Requests\Imports;

use App\Abstracts\Http\FormRequest as Request;

class Employee extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'birth_day' => 'required|date_format:Y-m-d',
            'gender' => 'required|string',
            'position_id' => 'required|integer',
            'amount' => 'required',
            'hired_at' => 'required|date_format:Y-m-d'
        ];
    }
}
