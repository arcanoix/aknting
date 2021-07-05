<?php

namespace Modules\Payroll\Listeners;

use Modules\Employees\Events\WidgetsCreating;
use Modules\Payroll\Widgets\PaychecksReceived;
use Modules\Payroll\Widgets\TotalBenefits;
use Modules\Payroll\Widgets\TotalSalary;

class ProvideWidgets
{
    public function handle(WidgetsCreating $event)
    {
        $event->dashboard_items->widgets[] = PaychecksReceived::class;
        $event->dashboard_items->widgets[] = TotalSalary::class;
        $event->dashboard_items->widgets[] = TotalBenefits::class;
    }
}
