<?php

namespace Modules\Payroll\Jobs\PayCalendar;

use App\Abstracts\Job;
use Modules\Payroll\Models\PayCalendar\Employee;

class CreatePayCalendarEmployee extends Job
{
    protected $request;

    protected $pay_calendar;

    /**
     * Create a new job instance.
     *
     * @param  $request
     * @param  $pay_calendar
     */
    public function __construct($request, $pay_calendar)
    {
        $this->request = $request;
        $this->pay_calendar = $pay_calendar;
    }

    /**
     * Execute the job.
     *
     * @return InvoiceItem
     */
    public function handle()
    {
        $employee = Employee::create([
            'company_id' => $this->pay_calendar->company_id,
            'pay_calendar_id' => $this->pay_calendar->id,
            'employee_id' => $this->request['employee_id'],
        ]);

        return $employee;
    }
}
