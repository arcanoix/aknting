<?php

namespace Modules\Payroll\Imports\RunPayrolls\Sheets;

use App\Abstracts\Import;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee as Model;

class RunPayrollEmployees extends Import
{
    public function model(array $row): Model
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $contact_id = Contact::where('name', $row['employee_name'])->pluck('id')->first();
        $employee_id = Employee::where('contact_id', $contact_id)->pluck('id')->first();

        if (!$employee_id) {
            return [];
        }

        $run_payroll = RunPayroll::where('name', $row['run_payroll_number'])->first();

        if (!$run_payroll) {
            return [];
        }

        $row['employee_id'] = $employee_id;
        $row['run_payroll_id'] = $run_payroll->id;
        $row['pay_calendar_id'] = $run_payroll->pay_calendar_id;
        $row['salary'] = $row['salary'] ?? 0;
        $row['benefit'] = $row['benefit'] ?? 0;
        $row['deduction'] = $row['deduction'] ?? 0;
        $row['total'] = $row['total'] ?? 0;

        return parent::map($row);
    }

    public function rules(): array
    {
        return [
            'employee_id'     => 'required',
            'pay_calendar_id' => 'required',
            'run_payroll_id'  => 'required',
        ];
    }
}
