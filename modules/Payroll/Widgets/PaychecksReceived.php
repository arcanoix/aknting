<?php

namespace Modules\Payroll\Widgets;

use App\Abstracts\Widget;
use App\Models\Common\Contact;
use Illuminate\Database\Eloquent\Builder;
use Modules\Employees\Models\Employee;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class PaychecksReceived extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('payroll::widgets.paychecks_received');
    }

    public function show()
    {
        return $this->view('payroll::widgets.paychecks_received', [
            'paychecks_received' => $this->getPaychecksCount(),
        ]);
    }

    public function getPaychecksCount()
    {
        $contact = Contact::where('user_id', user()->id)->first();
        if (!$contact) {
            return 0;
        }

        $employee = Employee::where('contact_id', $contact->id)->first();
        if (!$employee) {
            return 0;
        }

        return RunPayroll::where('status', 'approved')
            ->whereHas('employees', function (Builder $query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })
            ->count();
    }
}
