<?php

namespace Modules\Payroll\Imports\PayCalendars;

use App\Abstracts\ImportMultipleSheets;
use Modules\Payroll\Imports\PayCalendars\Sheets\PayCalendarEmployees;
use Modules\Payroll\Imports\PayCalendars\Sheets\PayCalendars as Base;

class PayCalendars extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'pay_calendars'          => new Base(),
            'pay_calendar_employees' => new PayCalendarEmployees(),
        ];
    }
}
