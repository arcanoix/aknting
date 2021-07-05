<?php

namespace Modules\Payroll\Exports\RunPayrolls\Sheets;

use App\Abstracts\Export;
use Modules\Payroll\Models\RunPayroll\RunPayroll as Model;

class RunPayrolls extends Export
{
    public function collection()
    {
        return Model::with('account:name', 'category:name', 'pay_calendar:name')
            ->collectForExport($this->ids, ['name' => 'desc']);
    }

    public function map($model): array
    {
        $model->run_payroll_number = $model->name;
        $model->category_name = $model->category->name;
        $model->account_name = $model->account->name;
        $model->pay_calendar_name = $model->pay_calendar->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'run_payroll_number',
            'from_date',
            'to_date',
            'payment_date',
            'pay_calendar_name',
            'category_name',
            'account_name',
            'payment_method',
            'currency_code',
            'currency_rate',
            'amount',
            'status',
        ];
    }
}
