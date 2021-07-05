<?php

namespace Modules\Payroll\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Report;
use Illuminate\Database\Seeder;

class Reports extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        $rows = [
            [
                'company_id' => $company_id,
                'class' => 'Modules\Payroll\Reports\EmployeeSummary',
                'name' => trans('payroll::general.employee_summary'),
                'description' => trans('payroll::general.description_employee_summary'),
                'settings' => ['group' => 'employee', 'period' => 'quarterly'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'Modules\Payroll\Reports\EmployeeDetailed',
                'name' => trans('payroll::general.employee_detailed'),
                'description' => trans('payroll::general.description_employee_detailed'),
                'settings' => ['group' => 'employee', 'period' => 'quarterly'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ExpenseSummary',
                'name' => trans('payroll::general.expense_summary'),
                'description' => trans('payroll::general.description_expense_summary'),
                'settings' => ['group' => 'employee', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}
