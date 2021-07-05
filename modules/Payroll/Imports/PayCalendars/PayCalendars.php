<?php

namespace Modules\Payroll\Imports\PayCalendars;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Payroll\Imports\PayCalendars\Sheets\PayCalendarEmployees;
use Modules\Payroll\Imports\PayCalendars\Sheets\PayCalendars as Base;

class PayCalendars implements ShouldQueue, WithChunkReading, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'pay_calendars'          => new Base(),
            'pay_calendar_employees' => new PayCalendarEmployees(),
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
