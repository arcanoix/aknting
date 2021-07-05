<?php

namespace Modules\Payroll\Exports\PayCalendars\Sheets;

use App\Abstracts\Export;
use Modules\Payroll\Models\PayCalendar\PayCalendar as Model;

class PayCalendars extends Export
{
    public function collection()
    {
        return Model::collectForExport($this->ids);
    }

    public function fields(): array
    {
        return [
            'name',
            'type',
            'pay_day_mode',
            'pay_day',
        ];
    }
}
