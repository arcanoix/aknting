<?php

namespace Modules\Payroll\Models\Employee;

use App\Abstracts\Model;

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
        'description'
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    public function employee()
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function pay_item()
    {
        return $this->hasOne('Modules\Payroll\Models\Setting\PayItem' , 'id', 'type');
    }
}
