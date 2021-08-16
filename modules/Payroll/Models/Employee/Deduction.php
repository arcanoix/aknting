<?php

namespace Modules\Payroll\Models\Employee;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Payroll\Models\RunPayroll\RunPayroll;

class Deduction extends Model
{
    protected $table = 'payroll_employee_deductions';

    protected $fillable = [
        'company_id',
        'employee_id',
        'type',
        'amount',
        'currency_code',
        'recurring',
        'description',
        'from_date',
        'to_date',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function pay_item(): HasOne
    {
        return $this->hasOne('Modules\Payroll\Models\Setting\PayItem', 'id', 'type');
    }

    public function run_payrolls(): BelongsToMany
    {
        return $this->belongsToMany(RunPayroll::class, 'payroll_run_payroll_employee_deductions', 'employee_deduction_id', 'run_payroll_id');
    }
}
