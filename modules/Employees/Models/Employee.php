<?php

namespace Modules\Employees\Models;

use App\Abstracts\Model;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use Cloneable, HasFactory, Media;

    protected $table = 'employees_employees';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'contact_id',
        'birth_day',
        'gender',
        'position_id',
        'amount',
        'hired_at',
        'bank_account_number',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    public $sortable = ['id'];

    /**
     * Cloneable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['contact'];

    public function contact(): BelongsTo
    {
        return $this->belongsTo('App\Models\Common\Contact');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function scopeEnabled($query): Builder
    {
        return $query
            ->join('contacts', 'employees_employees.contact_id', '=', 'contacts.id')
            ->where('contacts.enabled', 1)
            ->select('employees_employees.*');
    }

    public static function getAvailableGenders(): array
    {
        return [
            'male'   => trans('employees::employees.male'),
            'female' => trans('employees::employees.female'),
            'other'  => trans('employees::employees.other')
        ];
    }

    public function getAttachmentAttribute($value = null)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->all();
    }

    public static function newFactory(): Factory
    {
        return \Modules\Employees\Database\Factories\Employee::new();
    }
}
