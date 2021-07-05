<?php

namespace Modules\Payroll\Listeners;

use App\Listeners\Report\AddDate as Listener;

class AddDateToReports extends Listener
{
    protected $classes = [
        'Modules\Payroll\Reports\EmployeeSummary',
        'Modules\Payroll\Reports\EmployeeDetailed',
    ];
}
