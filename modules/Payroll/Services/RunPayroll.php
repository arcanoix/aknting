<?php

namespace Modules\Payroll\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Payroll\Models\Employee\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayroll as RunPayrollModel;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class RunPayroll
{
    /**
     * @var RunPayrollModel
     */
    private $run_payroll;

    public function __construct(RunPayrollModel $run_payroll)
    {
        $this->run_payroll = $run_payroll;
    }

    public function getEmployeesForSelectBox(): array
    {
        $employees = [];

        $run_payroll_employees = RunPayrollEmployee::where('run_payroll_id', $this->run_payroll->id)
            ->with('employee.contact')
            ->get();

        foreach ($run_payroll_employees as $run_payroll_employee) {
            $employees[$run_payroll_employee->employee_id] = $run_payroll_employee->employee->contact->name;
        }

        return $employees;
    }

    public function determineBenefits(Employee $employee): Collection
    {
        return $this->determinePayItems($employee, 'benefits');
    }

    public function determineDeductions(Employee $employee): Collection
    {
        return $this->determinePayItems($employee, 'deductions');
    }

    private function determinePayItems(Employee $employee, $type): Collection
    {
        $pay_items = $employee
            ->$type()
            ->with(['run_payrolls' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return $pay_items->filter(function ($pay_item) {
            if (!$this->payItemDateRangeFits($pay_item)) {
                return false;
            }

            switch ($pay_item->recurring) {
                case 'everymonth':
                    if ($this->monthlyPayItemIsAlreadyApplied($pay_item)) {
                        return false;
                    }
                    break;
                case 'onlyonce':
                    if ($this->oneTimePayItemIsAlreadyApplied($pay_item)) {
                        return false;
                    }
            }

            return true;
        });
    }

    private function payItemDateRangeFits($pay_item): bool
    {
        if (empty($pay_item->from_date) && empty($pay_item->to_date)) {
            return true;
        }

        $pay_item_from_date = $pay_item->from_date ?? 1;
        $pay_item_to_date = $pay_item->to_date ?? 31;

        $from_date = $this->run_payroll->from_date->day;
        $to_date = $this->run_payroll->to_date->day;

        if ($pay_item_from_date >= $from_date && $pay_item_from_date <= $to_date) {
            return true;
        }

        if ($pay_item_to_date >= $from_date && $pay_item_to_date <= $to_date) {
            return true;
        }

        return false;
    }

    private function monthlyPayItemIsAlreadyApplied($pay_item): bool
    {
        $start = $this->run_payroll->from_date->startOfMOnth();
        $end = $this->run_payroll->to_date->endOfMOnth();

        foreach ($pay_item->run_payrolls as $run_payroll) {
            if ($run_payroll->from_date->startOfMOnth()->between($start, $end) || $run_payroll->to_date->endOfMOnth()->between($start, $end)) {
                return true;
            }
        }

        return false;
    }

    private function oneTimePayItemIsAlreadyApplied($pay_item): bool
    {
        return $pay_item->run_payrolls->count() > 0;
    }
}
