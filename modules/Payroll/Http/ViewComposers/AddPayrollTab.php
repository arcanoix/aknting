<?php

namespace Modules\Payroll\Http\ViewComposers;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modules\Payroll\Models\Employee\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class AddPayrollTab
{
    private $view;

    public function compose(View $view)
    {
        if (!user()->can('read-payroll-payroll')) {
            return;
        }

        $this->view = $view;

        $this->showTab();
        $this->showContent();
    }

    private function showTab(): void
    {
        $this->view->getFactory()->startPush(
            'employee_profile_tab_end',
            view('payroll::partials.employee.tab')
        );
    }

    private function showContent(): void
    {
        $employee = $this->view->getData()['employee'];
        $employee = Employee::findOrFail($employee->id);

        $payments = $this->getPayments($employee);

        $totalPayment = $totalDeduction = $totalBenefit = 0;
        foreach ($payments as $payment) {
            $totalPayment += $payment->total;
            $totalDeduction += $payment->deduction;
            $totalBenefit += $payment->benefit;
        }

        $this->view->getFactory()->startPush(
            'employee_profile_content_end',
            view('payroll::partials.employee.tab_content', compact('employee', 'payments', 'totalPayment', 'totalDeduction', 'totalBenefit'))
        );
    }

    private function getPayments(Employee $employee): Collection
    {
        return RunPayrollEmployee::where('employee_id', $employee->id)
            ->with('run_payroll')
            ->whereHas('run_payroll', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();
    }
}
