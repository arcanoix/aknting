<?php

namespace Modules\Payroll\Imports\RunPayrolls;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployeeBenefits;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployeeDeductions;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrollEmployees;
use Modules\Payroll\Imports\RunPayrolls\Sheets\RunPayrolls as Base;

class RunPayrolls implements ShouldQueue, WithChunkReading, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'run_payrolls'                    => new Base(),
            'run_payroll_employees'           => new RunPayrollEmployees(),
            'run_payroll_employee_benefits'   => new RunPayrollEmployeeBenefits(),
            'run_payroll_employee_deductions' => new RunPayrollEmployeeDeductions(),
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
