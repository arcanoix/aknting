<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction;

class CreateRunPayrollEmployees extends Job
{
    protected $pay_calendar;

    protected $run_payroll;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param $pay_calendar
     * @param $run_payroll
     * @param $request
     */
    public function __construct($pay_calendar, $run_payroll, $request)
    {
        $this->pay_calendar = $pay_calendar;
        $this->run_payroll = $run_payroll;
        $this->request = $request;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $grand_total = 0;

        foreach ($this->pay_calendar->employees as $employee) {
            $total = $employee->employee->amount + $employee->employee->total_benefits - $employee->employee->total_deductions;

            $currency = $employee->employee->contact->currency;
            $converted_total = money($total, $currency->code)
                ->convert(currency($this->run_payroll->currency_code), $currency->rate)
                ->getAmount();
            $grand_total += $converted_total;

            RunPayrollEmployee::create([
                'company_id' => $this->pay_calendar->company_id,
                'employee_id' => $employee->employee_id,
                'pay_calendar_id' => $this->pay_calendar->id,
                'run_payroll_id' => $this->run_payroll->id,
                'salary' => $employee->employee->amount,
                'benefit' => $employee->employee->total_benefits,
                'deduction' => $employee->employee->total_deductions,
                'total' => $total
            ]);

            $benefits = $employee->employee->benefits()->where('status', 'not_approved')->get();

            foreach ($benefits as $benefit) {
                RunPayrollEmployeeBenefit::create([
                    'company_id' => $this->run_payroll->company_id,
                    'employee_id' => $employee->employee_id,
                    'employee_benefit_id' => $benefit->id,
                    'pay_calendar_id' => $this->pay_calendar->id,
                    'run_payroll_id' => $this->run_payroll->id,
                    'type' => $benefit->type,
                    'amount' => $benefit->amount,
                    'currency_code' => $currency->code,
                    'currency_rate' => $currency->rate
                ]);
            }

            $deductions = $employee->employee->deductions()->where('status', 'not_approved')->get();

            foreach ($deductions as $deduction) {
                RunPayrollEmployeeDeduction::create([
                    'company_id' => $this->run_payroll->company_id,
                    'employee_id' => $employee->employee_id,
                    'employee_benefit_id' => $deduction->id,
                    'pay_calendar_id' => $this->pay_calendar->id,
                    'run_payroll_id' => $this->run_payroll->id,
                    'type' => $deduction->type,
                    'amount' => $deduction->amount,
                    'currency_code' => $currency->code,
                    'currency_rate' => $currency->rate
                ]);
            }
        }

        $this->request['grand_total'] = $grand_total;
    }
}
