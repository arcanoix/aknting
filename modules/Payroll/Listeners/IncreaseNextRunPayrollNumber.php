<?php

namespace Modules\Payroll\Listeners;

use Modules\Payroll\Events\RunPayrollCreated as Event;
use Modules\Payroll\Traits\RunPayrolls;

class IncreaseNextRunPayrollNumber
{
    use RunPayrolls;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Update next run payroll number
        $this->increaseNextRunPayrollNumber();
    }
}
