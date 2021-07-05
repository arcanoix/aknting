<?php

namespace Modules\Payroll\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class PayCalendar extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string',
            'type'         => 'required|string',
            'pay_day_mode' => 'required|string|in:last_day,specific_day,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'pay_day'      => 'nullable|numeric|between:1,31',
            'employees'    => 'required|array',
        ];
    }
}
