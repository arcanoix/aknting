<?php

namespace Modules\Payroll\Imports\RunPayrolls\Sheets;

use App\Abstracts\Import;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit as Model;
use Modules\Payroll\Models\Setting\PayItem;

class RunPayrollEmployeeBenefits extends Import
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

        $type = PayItem::where('pay_item', $row['benefit_type'])->pluck('id')->first();

        $row['employee_id'] = $employee_id;
        $row['run_payroll_id'] = $run_payroll->id;
        $row['pay_calendar_id'] = $run_payroll->pay_calendar_id;
        $row['type'] = $type;

        return parent::map($row);
    }

    public function rules(): array
    {
        return [
            'employee_id'     => 'required',
            'pay_calendar_id' => 'required',
            'run_payroll_id'  => 'required',
            'type'            => 'required',
            'amount'          => 'required|numeric',
            'currency_code'   => 'required|string',
            'currency_rate'   => 'required|numeric',
        ];
    }
}
