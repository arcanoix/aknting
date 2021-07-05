<?php

namespace Modules\Payroll\Exports\PayCalendars;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Payroll\Exports\PayCalendars\Sheets\PayCalendarEmployees;
use Modules\Payroll\Exports\PayCalendars\Sheets\PayCalendars as Base;

class PayCalendars implements WithMultipleSheets
{
    use Exportable;

    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            new Base($this->ids),
            new PayCalendarEmployees($this->ids),
        ];
    }
}
