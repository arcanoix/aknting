<?php

namespace Modules\Payroll\Exports\RunPayrolls\Sheets;

use App\Abstracts\Export;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee as Model;

class RunPayrollEmployees extends Export
{
    public function collection()
    {
        return Model::with('run_payroll:name', 'employee.contact:name')
            ->collectForExport($this->ids, ['run_payroll.name' => 'desc'], 'run_payroll_id');
    }

    public function map($model): array
    {
        $model->run_payroll_number = $model->run_payroll->name;
        $model->employee_name = $model->employee->contact->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'run_payroll_number',
            'employee_name',
            'salary',
            'benefit',
            'deduction',
            'total',
        ];
    }
}
