<?php

namespace Modules\Payroll\Events;

use Illuminate\Queue\SerializesModels;

class RunPayrollCreated
{
    use SerializesModels;

    public $run_payroll;

    /**
     * Create a new event instance.
     *
     * @param $run_payroll
     */
    public function __construct($run_payroll)
    {
        $this->run_payroll = $run_payroll;
    }
}
