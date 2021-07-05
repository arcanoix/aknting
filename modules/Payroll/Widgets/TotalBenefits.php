<?php

namespace Modules\Payroll\Widgets;

use App\Abstracts\Widget;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class TotalBenefits extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('payroll::widgets.total_benefits');
    }

    public function show()
    {
        return $this->view('payroll::widgets.total_benefits', [
            'total_benefits' => $this->getTotalBenefitsReceived(),
        ]);
    }

    public function getTotalBenefitsReceived()
    {
        $contact = Contact::where('user_id', user()->id)->first();
        if (!$contact) {
            return 0;
        }

        $employee = Employee::where('contact_id', $contact->id)->first();
        if (!$employee) {
            return 0;
        }

        return RunPayrollEmployee::where('employee_id', $employee->id)
            ->whereHas('run_payroll', function ($query) {
                $query->where('status', 'approved');
            })
            ->sum('benefit');
    }
}
