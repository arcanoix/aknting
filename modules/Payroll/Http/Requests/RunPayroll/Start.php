<?php

namespace Modules\Payroll\Http\Requests\RunPayroll;

use App\Abstracts\Http\FormRequest as Request;

class Start extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = null;

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = $this->run_payroll->getAttribute('id');
        }

        // Get company id
        $company_id = $this->request->get('company_id');

        return [
            'name'         => 'required|string|unique:payroll_run_payrolls,NULL,' . $id . ',id,company_id,' . $company_id . ',deleted_at,NULL',
            'from_date'    => 'required|date|before_or_equal:to_date',
            'to_date'      => 'required|date|after_or_equal:from_date',
            'payment_date' => 'required|date',
        ];
    }
}
