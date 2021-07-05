<?php

namespace Modules\Payroll\Reports;

use App\Abstracts\Report;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class EmployeeSummary extends Report
{
    public $default_name = 'payroll::general.employee_summary';

    public $category = 'payroll::general.name';

    public $icon = 'fa fa-users';

    public $columns = [];

    public function setViews()
    {
        parent::setViews();
        $this->views['table.header'] = 'payroll::partials.reports.table.header';
    }

    public function setData()
    {
        $query = RunPayroll::where('status', 'approved')
            ->join('payroll_run_payroll_employees', 'payroll_run_payrolls.id', '=', 'payroll_run_payroll_employees.run_payroll_id')
            ->join('employees_employees', 'payroll_run_payroll_employees.employee_id', '=', 'employees_employees.id')
            ->select(
                'payroll_run_payrolls.payment_date',
                'payroll_run_payrolls.currency_code',
                'payroll_run_payrolls.currency_rate',
                'payroll_run_payroll_employees.salary',
                'payroll_run_payroll_employees.benefit',
                'payroll_run_payroll_employees.deduction',
                'employees_employees.contact_id'
            );
        $run_payroll_employees = $this->applyFilters($query, [
            'date_field' => 'payment_date',
            'employee' => 'contact_id',
        ])->get();

        $this->setTotals($run_payroll_employees, '');
    }

    public function setTotals($items, $date_field, $check_type = false, $table = 'default', $with_tax = true)
    {
        foreach ($items as $item) {
            // Make groups extensible
            $item = $this->applyGroups($item);

            $id_field = $this->getSetting('group') . '_id';

            if (!isset($this->row_values[$table][$item->$id_field])) {
                continue;
            }

            $salary = $item->convertToDefault(floatval($item->salary), $item->currency_code, $item->currency_rate);
            $benefit = $item->convertToDefault(floatval($item->benefit), $item->currency_code, $item->currency_rate);
            $deduction = $item->convertToDefault(floatval($item->deduction), $item->currency_code, $item->currency_rate);

            $this->row_values[$table][$item->$id_field][$this->columns[1]] += $salary;
            $this->row_values[$table][$item->$id_field][$this->columns[2]] += $benefit;
            $this->row_values[$table][$item->$id_field][$this->columns[3]] -= $deduction;

            $this->footer_totals[$table][$this->columns[1]] += $salary;
            $this->footer_totals[$table][$this->columns[2]] += $benefit;
            $this->footer_totals[$table][$this->columns[3]] -= $deduction;
        }
    }

    public function setColumns()
    {
        $this->columns[1] = trans_choice('payroll::general.salaries', 1);
        $this->columns[2] = trans_choice('payroll::general.benefits', 1);
        $this->columns[3] = trans_choice('payroll::general.deductions', 1);

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
        $this->column_name_width = 'col-sm-3';
        $this->column_value_width = 'col-sm-2';
    }
}
