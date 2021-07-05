<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class DuplicateRunPayroll extends Job
{
    protected $run_payroll;

    /**
     * Create a new job instance.
     *
     * @param  $run_payroll
     */
    public function __construct($run_payroll)
    {
        $this->run_payroll = $run_payroll;
    }

    /**
     * Execute the job.
     *
     * @return RunPayroll
     */
    public function handle()
    {
        $clone = $this->run_payroll->duplicate();

        return $clone;
    }
}
