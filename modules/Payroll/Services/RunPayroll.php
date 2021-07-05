<?php

namespace Modules\Payroll\Services;

use Modules\Payroll\Models\RunPayroll\RunPayroll as RunPayrollModel;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class RunPayroll
{
    /**
     * @var RunPayrollModel
     */
    private $runPayroll;

    public function __construct(RunPayrollModel $runPayroll)
    {
        $this->runPayroll = $runPayroll;
    }

    /**
     * @return array
     */
    public function getEmployeesForSelectBox()
    {
        $employees = [];

        $run_payroll_employees = RunPayrollEmployee::where('run_payroll_id', $this->runPayroll->id)
            ->with('employee.contact')
            ->get();

        foreach ($run_payroll_employees as $run_payroll_employee) {
            $employees[$run_payroll_employee->employee_id] = $run_payroll_employee->employee->contact->name;
        }

        return $employees;
    }
}
