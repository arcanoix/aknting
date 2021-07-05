<?php

namespace Modules\Payroll\Jobs\RunPayroll;

use App\Abstracts\Job;

class DeleteRunPayroll extends Job
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
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->deleteRelationships($this->run_payroll, [
                'benefits',
                'deductions',
                'employees',
            ]);

            $this->run_payroll->payment()->delete();

            $this->run_payroll->delete();
        });

        return true;
    }
}
