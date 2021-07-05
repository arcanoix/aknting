<?php

namespace Modules\Payroll\Imports\PayCalendars\Sheets;

use App\Abstracts\Import;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee;
use Modules\Payroll\Models\PayCalendar\Employee as Model;
use Modules\Payroll\Models\PayCalendar\PayCalendar;

class PayCalendarEmployees extends Import
{
    public function model(array $row): Model
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $pay_calendar_id = PayCalendar::where('name', $row['pay_calendar_name'])->pluck('id')->first();

        if (!$pay_calendar_id) {
            return [];
        }

        $contact_id = Contact::where('name', $row['employee_name'])->pluck('id')->first();
        $employee_id = Employee::where('contact_id', $contact_id)->pluck('id')->first();

        if (!$employee_id) {
            return [];
        }

        $row['pay_calendar_id'] = $pay_calendar_id;
        $row['employee_id'] = $employee_id;

        return parent::map($row);
    }

    public function rules(): array
    {
        return [
            'pay_calendar_name' => 'required|string',
            'employee_name'     => 'required|string',
        ];
    }
}
