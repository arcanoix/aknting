<?php

namespace Modules\Payroll\Imports\RunPayrolls\Sheets;

use App\Abstracts\Import;
use Modules\Payroll\Http\Requests\RunPayroll\Start as Request;
use Modules\Payroll\Models\PayCalendar\PayCalendar;
use Modules\Payroll\Models\RunPayroll\RunPayroll as Model;

class RunPayrolls extends Import
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

        $row['name'] = $row['run_payroll_number'];
        $row['pay_calendar_id'] = $pay_calendar_id;
        $row['category_id'] = $this->getCategoryId($row, 'other');
        $row['account_id'] = $this->getAccountId($row);

        return parent::map($row);
    }

    public function rules(): array
    {
        $rules = array_merge((new Request())->rules(), [
            'pay_calendar_id' => 'required',
            'category_id'     => 'required',
            'account_id'      => 'required',
            'payment_method'  => 'required|string',
            'currency_code'   => 'required|string',
            'currency_rate'   => 'required|numeric',
            'amount'          => 'required|numeric',
            'status'          => 'required|string|in:not_approved,approved',
        ]);

        return $this->replaceForBatchRules($rules);
    }
}
