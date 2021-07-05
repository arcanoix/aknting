<?php

namespace Modules\Payroll\Models\Setting;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayItem extends Model
{
    use HasFactory;

    protected $table = 'payroll_setting_pay_items';

    protected $fillable = [
        'company_id',
        'pay_type',
        'pay_item',
        'amount_type',
        'code'
    ];

    public static function newFactory(): Factory
    {
        return \Modules\Payroll\Database\Factories\PayItem::new();
    }
}
