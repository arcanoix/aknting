<?php

namespace Modules\Payroll\Jobs\PayCalendar;

use App\Abstracts\Job;

class DeletePayCalendar extends Job
{
    protected $pay_calendar;

    /**
     * Create a new job instance.
     *
     * @param  $pay_calendar
     */
    public function __construct($pay_calendar)
    {
        $this->pay_calendar = $pay_calendar;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->deleteRelationships($this->pay_calendar, [
            'employees',
        ]);

        $this->pay_calendar->delete();

        return true;
    }
}
