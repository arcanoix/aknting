<?php

namespace Modules\Payroll\Models\RunPayroll;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunPayrollEmployeeDeduction extends Model
{
    protected $table = 'payroll_run_payroll_employee_deductions';

    protected $fillable = [
        'company_id',
        'employee_id',
        'employee_deduction_id',
        'pay_calendar_id',
        'run_payroll_id',
        'type',
        'amount',
        'currency_code',
        'currency_rate',
        'description'
    ];

    protected $casts = [
        'amount' => 'double',
        'currency_rate' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Deduction', 'employee_deduction_id');
    }

    public function pay_calendar(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\PayCalendar\PayCalendar');
    }

    public function run_payroll(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\RunPayroll\RunPayroll');
    }

    public function pay_item()
    {
        return $this->hasOne('Modules\Payroll\Models\Setting\PayItem', 'id', 'type');
    }
}
