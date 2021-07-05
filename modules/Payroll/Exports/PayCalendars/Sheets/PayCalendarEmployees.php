<?php

namespace Modules\Payroll\Exports\PayCalendars\Sheets;

use App\Abstracts\Export;
use Modules\Payroll\Models\PayCalendar\Employee as Model;

class PayCalendarEmployees extends Export
{
    public function collection()
    {
        return Model::with('employee.contact:name', 'pay_calendar')
            ->collectForExport($this->ids, 'pay_calendar.name', 'pay_calendar_id');
    }

    public function map($model): array
    {
        $model->pay_calendar_name = $model->pay_calendar->name;
        $model->employee_name = $model->employee->contact->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'pay_calendar_name',
            'employee_name',
        ];
    }
}
