<?php

namespace Modules\Payroll\Models\Employee;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Employees\Models\Employee as Model;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class Employee extends Model
{
    public function benefits(): HasMany
    {
        return $this->hasMany(Benefit::class, 'employee_id', 'id');
    }

    public function deductions(): HasMany
    {
        return $this->hasMany(Deduction::class, 'employee_id', 'id');
    }

    public function payrollPayment(): HasMany
    {
        return $this->hasMany(RunPayrollEmployee::class, 'employee_id', 'id');
    }

    public function getTotalBenefitsAttribute(): float
    {
        return $this->benefits()
            ->where('status', 'not_approved')
            ->sum('amount');
    }

    public function getTotalDeductionsAttribute(): float
    {
        return $this->deductions()
            ->where('status', 'not_approved')
            ->sum('amount');
    }

    public function getTotalsAttribute(): float
    {
        return ($this->amount + $this->total_benefits) - $this->total_deductions;
    }
}
