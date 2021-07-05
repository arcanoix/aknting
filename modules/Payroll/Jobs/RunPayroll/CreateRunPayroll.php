<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Events\RunPayrollCreated;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class CreateRunPayroll extends Job
{
    protected $pay_calendar;

    protected $request;

    protected $run_payroll;

    /**
     * Create a new job instance.
     *
     * @param $pay_calendar
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
     * @return RunPayroll
     * @throws \Throwable
     */
    public function handle()
    {
        $this->prepareRequest();

        DB::transaction(function () {
            $this->run_payroll = RunPayroll::create($this->request->all());

            $this->dispatch(new CreateRunPayrollEmployees($this->pay_calendar, $this->run_payroll, $this->request));

            $this->run_payroll->update([
                'amount' => $this->request['grand_total'],
            ]);
        });

        event(new RunPayrollCreated($this->run_payroll));

        return $this->run_payroll;
    }

    protected function prepareRequest()
    {
        $this->request['payment_id'] = null;
        $this->request['status'] = 'not_approved';
        $this->request['amount'] = '0';
        $this->request['name'] = (setting('payroll.name') != $this->request['name']) ? $this->request['name'] : $this->request['name'] . '-' . rand(1, 10000);
    }
}
