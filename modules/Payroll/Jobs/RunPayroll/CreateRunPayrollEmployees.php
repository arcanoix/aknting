<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction;
use Modules\Payroll\Services\RunPayroll as RunPayrollService;

class CreateRunPayrollEmployees extends Job
{
    protected $pay_calendar;

    protected $run_payroll;

    protected $request;

    protected $run_payroll_service;

    public function __construct($pay_calendar, $run_payroll, $request)
    {
        $this->pay_calendar = $pay_calendar;
        $this->run_payroll = $run_payroll;
        $this->request = $request;

        $this->run_payroll_service = new RunPayrollService($run_payroll);
    }

    public function handle()
    {
        $grand_total = 0;

        foreach ($this->pay_calendar->employees as $pay_calendar_employee) {
            $employee = $pay_calendar_employee->employee;

            $benefits = $this->run_payroll_service->determineBenefits($employee);

            $deductions = $this->run_payroll_service->determineDeductions($employee);

            $benefits_sum = $benefits->sum('amount');

            $deductions_sum = $deductions->sum('amount');

            $total = $employee->amount + $benefits_sum - $deductions_sum;

            RunPayrollEmployee::create([
                'company_id'      => $this->pay_calendar->company_id,
                'employee_id'     => $employee->id,
                'pay_calendar_id' => $this->pay_calendar->id,
                'run_payroll_id'  => $this->run_payroll->id,
                'salary'          => $employee->amount,
                'benefit'         => $benefits_sum,
                'deduction'       => $deductions_sum,
                'total'           => $total
            ]);

            $currency = $employee->contact->currency;

            foreach ($benefits as $benefit) {
                RunPayrollEmployeeBenefit::create([
                    'company_id'          => $this->run_payroll->company_id,
                    'employee_id'         => $employee->id,
                    'employee_benefit_id' => $benefit->id,
                    'pay_calendar_id'     => $this->pay_calendar->id,
                    'run_payroll_id'      => $this->run_payroll->id,
                    'type'                => $benefit->type,
                    'amount'              => $benefit->amount,
                    'currency_code'       => $currency->code,
                    'currency_rate'       => $currency->rate
                ]);
            }

            foreach ($deductions as $deduction) {
                RunPayrollEmployeeDeduction::create([
                    'company_id'            => $this->run_payroll->company_id,
                    'employee_id'           => $employee->id,
                    'employee_deduction_id' => $deduction->id,
                    'pay_calendar_id'       => $this->pay_calendar->id,
                    'run_payroll_id'        => $this->run_payroll->id,
                    'type'                  => $deduction->type,
                    'amount'                => $deduction->amount,
                    'currency_code'         => $currency->code,
                    'currency_rate'         => $currency->rate
                ]);
            }

            $converted_total = money($total, $currency->code)
                ->convert(currency($this->run_payroll->currency_code), $currency->rate)
                ->getAmount();

            $grand_total += $converted_total;
        }

        $this->request['grand_total'] = $grand_total;
    }
}
