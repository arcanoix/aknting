<?php

namespace Modules\Payroll\Models\RunPayroll;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunPayrollEmployee extends Model
{
    protected $table = 'payroll_run_payroll_employees';

    protected $fillable = [
        'company_id',
        'employee_id',
        'pay_calendar_id',
        'run_payroll_id',
        'salary',
        'benefit',
        'deduction',
        'total'
    ];

    protected $casts = [
        'salary' => 'double',
        'benefit' => 'double',
        'deduction' => 'double',
        'total' => 'double'
    ];

    public function employee()
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function benefits()
    {
        return $this->hasMany('Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit', 'employee_id', 'employee_id');
    }

    public function deductions()
    {
        return $this->hasMany('Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction', 'employee_id', 'employee_id');
    }

    public function pay_calendar()
    {
        return $this->belongsTo('Modules\Payroll\Models\PayCalendar\PayCalendar');
    }

    public function run_payroll(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\RunPayroll\RunPayroll');
    }

    public function getTotalBenefitsAttribute()
    {
        $total_benefits = $this->benefits()
            ->where('status', 'not_approved')
            ->sum('amount');

        return $total_benefits;
    }

    public function getTotalDeductionsAttribute()
    {
        $total_deductions = $this->deductions('not_approved')
            ->where('status', 'not_approved')
            ->sum('amount');

        return $total_deductions;
    }

    public function getTotalsAttribute()
    {
        $totals = ($this->amount + $this->total_benefits) - $this->total_deductions;

        return $totals;
    }

}
