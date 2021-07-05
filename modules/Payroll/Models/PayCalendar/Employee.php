<?php

namespace Modules\Payroll\Models\PayCalendar;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'payroll_pay_calendar_employees';

    protected $fillable = [
        'company_id',
        'pay_calendar_id',
        'employee_id'
    ];

    public $sortable = ['id'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\Employee\Employee');
    }

    public function pay_calendar(): BelongsTo
    {
        return $this->belongsTo(PayCalendar::class);
    }
}
