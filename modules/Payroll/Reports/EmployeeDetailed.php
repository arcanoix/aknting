<?php

namespace Modules\Payroll\Reports;

use App\Abstracts\Report;
use Modules\Payroll\Models\RunPayroll\RunPayroll;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction;
use Modules\Payroll\Models\Setting\PayItem;

class EmployeeDetailed extends Report
{
    public $default_name = 'payroll::general.employee_detailed';

    public $category = 'payroll::general.name';

    public $icon = 'fa fa-users';

    public $columns = [];

    public function setViews()
    {
        parent::setViews();
        $this->views['table.footer'] = 'payroll::partials.reports.table.employee_detailed_footer';
        $this->views['table.header'] = 'payroll::partials.reports.table.header';
        $this->views['table.rows'] = 'payroll::partials.reports.table.employee_detailed_rows';
    }

    public function setData()
    {
        $query = RunPayroll::where('status', 'approved')
            ->join('payroll_run_payroll_employees', 'payroll_run_payrolls.id', '=', 'payroll_run_payroll_employees.run_payroll_id')
            ->join('employees_employees', 'payroll_run_payroll_employees.employee_id', '=', 'employees_employees.id')
            ->select(
                'payroll_run_payrolls.id as run_payroll_id',
                'payroll_run_payrolls.payment_date',
                'payroll_run_payrolls.currency_code',
                'payroll_run_payrolls.currency_rate',
                'payroll_run_payroll_employees.salary',
                'payroll_run_payroll_employees.employee_id',
                'employees_employees.contact_id'
            );
        $run_payroll_employees = $this->applyFilters($query, [
            'date_field' => 'payment_date',
            'employee' => 'contact_id',
        ])->get();

        foreach ($run_payroll_employees as $run_payroll_employee) {
            $run_payroll_employee->benefits = RunPayrollEmployeeBenefit::where([
                    ['run_payroll_id', '=', $run_payroll_employee->run_payroll_id],
                    ['employee_id', '=', $run_payroll_employee->employee_id],
                ])
                ->get();
            $run_payroll_employee->deductions = RunPayrollEmployeeDeduction::where([
                    ['run_payroll_id', '=', $run_payroll_employee->run_payroll_id],
                    ['employee_id', '=', $run_payroll_employee->employee_id],
                ])
                ->get();
        }

        $this->setTotals($run_payroll_employees, '');
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        if (!isset($this->row_values[$table])) {
            return;
        }

        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $id_field = $this->getSetting('group') . '_id';

            if (!isset($this->row_values[$table][$item->$id_field])) {
                continue;
            }

            $salary = $item->convertToDefault(floatval($item->salary), $item->currency_code, $item->currency_rate);
            $benefit = $item->convertToDefault($item->benefits->sum('amount'), $item->currency_code, $item->currency_rate);
            $deduction = $item->convertToDefault($item->deductions->sum('amount'), $item->currency_code, $item->currency_rate);

            $this->row_values[$table][$item->$id_field][$this->columns[1]] += $salary;
            $this->row_values[$table][$item->$id_field][$this->columns[2]] = '';
            $this->row_values[$table][$item->$id_field][$this->columns[3]] += $benefit;
            $this->row_values[$table][$item->$id_field][$this->columns[4]] = '';
            $this->row_values[$table][$item->$id_field][$this->columns[5]] -= $deduction;

            if (!isset($this->row_values[$table][$item->$id_field]['benefits'])) {
                $this->row_values[$table][$item->$id_field]['benefits'] = collect();
            }
            $this->row_values[$table][$item->$id_field]['benefits'] = $this->row_values[$table][$item->$id_field]['benefits']->merge($item->benefits);
            if (!isset($this->row_values[$table][$item->$id_field]['deductions'])) {
                $this->row_values[$table][$item->$id_field]['deductions'] = collect();
            }
            $this->row_values[$table][$item->$id_field]['deductions'] = $this->row_values[$table][$item->$id_field]['deductions']->merge($item->deductions);

            $this->footer_totals[$table][$this->columns[1]] += $salary;
            $this->footer_totals[$table][$this->columns[2]] = '';
            $this->footer_totals[$table][$this->columns[3]] += $benefit;
            $this->footer_totals[$table][$this->columns[4]] = '';
            $this->footer_totals[$table][$this->columns[5]] -= $deduction;
        }

        $pay_items = PayItem::pluck('pay_item', 'id');
        foreach ($this->row_values[$table] as $id => $row_value) {
            if (isset($row_value['benefits'])) {
                $this->row_values[$table][$id]['benefits'] = $row_value['benefits']->groupBy('type')
                    ->map(function ($item, $key) use ($pay_items) {
                        return [
                            'amount'   => $item->sum('amount'),
                            'pay_item' => $pay_items[$key],
                        ];
                    });
            }
            if (isset($row_value['deductions'])) {
                $this->row_values[$table][$id]['deductions'] = $row_value['deductions']->groupBy('type')
                    ->map(function ($item, $key) use ($pay_items) {
                        return [
                            'amount'   => $item->sum('amount'),
                            'pay_item' => $pay_items[$key],
                        ];
                    });
            }
        }
    }

    public function setColumns()
    {
        $this->columns[1] = trans_choice('payroll::general.salaries', 1);
        $this->columns[2] = trans_choice('payroll::general.benefits', 1);
        $this->columns[3] = trans('payroll::general.total', ['type' => trans_choice('payroll::general.benefits', 1)]);
        $this->columns[4] = trans_choice('payroll::general.deductions', 1);
        $this->columns[5] = trans('payroll::general.total', ['type' => trans_choice('payroll::general.deductions', 1)]);

        foreach ($this->columns as $column) {
            foreach ($this->tables as $table) {
                $this->footer_totals[$table][$column] = 0;
            }
        }

        $this->dates = $this->columns;
    }

    public function setDates()
    {
        $this->setColumns();
    }

    public function setColumnWidth()
    {
        $this->column_name_width = 'col-sm-1';
        $this->column_value_width = 'col-sm-2';
    }
}
