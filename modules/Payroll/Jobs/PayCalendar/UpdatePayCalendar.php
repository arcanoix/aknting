<?php

namespace Modules\Payroll\Jobs\PayCalendar;

use App\Abstracts\Job;
use Modules\Payroll\Models\PayCalendar\PayCalendar;

class UpdatePayCalendar extends Job
{
    protected $pay_calendar;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $pay_calendar
     * @param  $request
     */
    public function __construct($pay_calendar, $request)
    {
        $this->pay_calendar = $pay_calendar;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return PayCalendar
     */
    public function handle()
    {
        $this->createPayCalendarEmployee();

        $this->pay_calendar->update($this->request->all());

        return $this->pay_calendar;
    }

    protected function createPayCalendarEmployee()
    {
        // Delete current employees
        $this->deleteRelationships($this->pay_calendar, [
            'employees',
        ]);

        $employee = [];
        $pay_calendar_employees = [];

        foreach ((array) $this->request['employees'] as $employee_id) {
            $employee['pay_calendar_id'] = $this->pay_calendar->id;
            $employee['company_id'] = $this->pay_calendar->company_id;
            $employee['employee_id'] = $employee_id;

            $pay_calendar_employees[] = $this->dispatch(new CreatePayCalendarEmployee($employee, $this->pay_calendar));
        }

        return $pay_calendar_employees;
    }
}
