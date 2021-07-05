<?php

namespace Modules\Payroll\Models\PayCalendar;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayCalendar extends Model
{
    use HasFactory;

    use Cloneable, Currencies;

    protected $table = 'payroll_pay_calendars';

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'type_code',
        'pay_day_mode',
        'pay_day_mode_code',
        'pay_day',
    ];

    public $sortable = ['name', 'type'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['employees'];

    public function employees()
    {
        return $this->hasMany('Modules\Payroll\Models\PayCalendar\Employee', 'pay_calendar_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public static function getAvailableTypes(): array
    {
        return [
            'weekly' => trans('payroll::general.weekly'),
            'bi-weekly' => trans('payroll::general.bi-weekly'),
            'monthly' => trans('payroll::general.monthly')
        ];
    }

    public static function getPaydayModes(string $type): array
    {
        $monthly = [
            'last_day' => trans('payroll::general.last_day'),
            'specific_day' => trans('payroll::general.specific_day')
        ];

        $weekly = [
            'Monday' => trans('payroll::general.Monday'),
            'Tuesday' => trans('payroll::general.Tuesday'),
            'Wednesday' => trans('payroll::general.Wednesday'),
            'Thursday' => trans('payroll::general.Thursday'),
            'Friday' => trans('payroll::general.Friday'),
            'Saturday' => trans('payroll::general.Saturday'),
            'Sunday' => trans('payroll::general.Sunday'),
        ];

        return $type === 'monthly' ? $monthly : $weekly;
    }

    public static function newFactory(): Factory
    {
        return \Modules\Payroll\Database\Factories\PayCalendar::new();
    }
}
