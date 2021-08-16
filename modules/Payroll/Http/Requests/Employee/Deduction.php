<?php

namespace Modules\Payroll\Http\Requests\Employee;

use App\Abstracts\Http\FormRequest as Request;

class Deduction extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'      => 'required|string',
            'amount'    => 'required|numeric',
            'recurring' => 'required|string',
            'from_date' => [
                'nullable',
                'numeric',
                'min:1',
                'max:31',
                $this->input('to_date') ? 'lte:to_date' : '',
            ],
            'to_date'   => [
                'nullable',
                'numeric',
                'min:1',
                'max:31',
                $this->input('from_date') ? 'gte:from_date' : '',
            ],
        ];
    }
}
