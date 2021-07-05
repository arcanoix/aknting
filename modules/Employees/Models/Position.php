<?php

namespace Modules\Employees\Models;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    use Cloneable;

    protected $table = 'employees_positions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'enabled',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    protected $sortable = ['name', 'enabled'];

    public function employees()
    {
        return $this->hasMany('Modules\Employees\Models\Employee');
    }

    public static function newFactory(): Factory
    {
        return \Modules\Employees\Database\Factories\Position::new();
    }
}
