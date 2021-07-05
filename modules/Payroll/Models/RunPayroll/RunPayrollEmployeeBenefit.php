<?php

namespace Modules\Payroll\Models\RunPayroll;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RunPayrollEmployeeBenefit extends Model
{
    protected $table = 'payroll_run_payroll_employee_benefits';

    protected $fillable = [
        'company_id',
        'employee_id',
        'employee_benefit_id',
        'pay_calendar_id',
        'run_payroll_id',
        'type',
        'amount',
        'currency_code',
        'currency_rate',
        'description'
    ];

    protected $casts = [
        'amount'        => 'double',
        'currency_rate' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function benefit(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Benefit', 'employee_benefit_id');
    }

    public function pay_calendar(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\PayCalendar\PayCalendar');
    }

    public function run_payroll(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\RunPayroll\RunPayroll');
    }

    public function pay_item(): HasOne
    {
        return $this->hasOne('Modules\Payroll\Models\Setting\PayItem', 'id', 'type');
    }
}
