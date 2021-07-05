<?php

namespace Modules\Payroll\Models\Employee;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Benefit extends Model
{
    protected $table = 'payroll_employee_benefits';

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

    public function employee(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function pay_item(): HasOne
    {
        return $this->hasOne('Modules\Payroll\Models\Setting\PayItem' , 'id', 'type');
    }
}
