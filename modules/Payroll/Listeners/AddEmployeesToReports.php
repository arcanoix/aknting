<?php

namespace Modules\Payroll\Listeners;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use App\Traits\Currencies;

class AddEmployeesToReports extends Listener
{
    use Currencies;

    protected $classes = [
        'App\Reports\ExpenseSummary',
        'Modules\Payroll\Reports\EmployeeSummary',
        'Modules\Payroll\Reports\EmployeeDetailed',
    ];

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterShowing(FilterShowing $event)
    {
        $classes = [
            'Modules\Payroll\Reports\EmployeeSummary',
            'Modules\Payroll\Reports\EmployeeDetailed',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->class->filters['employee'] = $this->getContacts('employee');
        $event->class->filters['routes']['employee'] = 'employees.employees.index';
        $event->class->filters['names']['employee'] = trans_choice('employees::general.employees', 1);
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        $classes = [
            'Modules\Payroll\Reports\EmployeeSummary',
            'Modules\Payroll\Reports\EmployeeDetailed',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->model->when(request('employee'), function ($query, $employee) use ($event) {
            return $query->where($event->args['employee'], $employee);
        });
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->groups['employee'] = trans_choice('payroll::general.employees', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupApplying(GroupApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->model->employee_id = $event->model->contact_id;
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'employee')) {
            return;
        }

        $employee_list = $this->getContacts('employee');

        if ($employees = request('employees')) {
            $rows = collect($employee_list)->filter(function ($value, $key) use ($employees) {
                return in_array($key, $employees);
            });
        } else {
            $rows = $employee_list;
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
