<?php

namespace Modules\Pos\Models;

use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Builder;
use Laratrust\Contracts\Ownable;

class Order extends Document implements Ownable
{
    public const TYPE = 'pos-order';

    protected static function booted()
    {
        static::addGlobalScope(self::TYPE, function (Builder $builder) {
            $builder->where('type', self::TYPE);
        });
    }

    public function getTemplatePathAttribute($value = null)
    {
        return $value ?: 'pos::orders.print_default';
    }

//    protected static function newFactory(): Factory
//    {
//        return \Modules\Pos\Database\Factories\Order::new();
//    }
}
