<?php

namespace Modules\Payroll\Jobs\PayCalendar;

use App\Abstracts\Job;
use Modules\Payroll\Models\PayCalendar\PayCalendar;

class CreatePayCalendar extends Job
{
    protected $request;

    protected $pay_calendar;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return PayCalendar
     */
    public function handle()
    {
        $this->pay_calendar = PayCalendar::create($this->request->all());

        $this->createPayCalendarEmployee();

        return $this->pay_calendar;
    }

    protected function createPayCalendarEmployee()
    {
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
