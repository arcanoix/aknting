<?php

namespace Modules\Payroll\Imports\RunPayrolls;

use App\Abstracts\ImportMultipleSheets;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployeeBenefits;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployeeDeductions;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployees;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrolls as Base;

class RunPayrolls extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'run_payrolls'                    => new Base(),
            'run_payroll_employees'           => new RunPayrollEmployees(),
            'run_payroll_employee_benefits'   => new RunPayrollEmployeeBenefits(),
            'run_payroll_employee_deductions' => new RunPayrollEmployeeDeductions(),
        ];
    }
}
