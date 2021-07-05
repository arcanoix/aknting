<?php

namespace Modules\Payroll\Models\RunPayroll;

use App\Abstracts\Model;
use App\Models\Banking\Transaction;
use App\Models\Common\Media as MediaModel;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunPayroll extends Model
{
    use HasFactory, Cloneable, Currencies, DateTime, Media;

    protected $table = 'payroll_run_payrolls';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'from_date',
        'to_date',
        'payment_date',
        'payment_id',
        'pay_calendar_id',
        'category_id',
        'account_id',
        'payment_method',
        'currency_code',
        'currency_rate',
        'amount',
        'status'
    ];

    protected $casts = [
        'amount'        => 'double',
        'currency_rate' => 'double',
    ];

    protected $dates = ['from_date', 'to_date', 'payment_date'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'from_date', 'to_date', 'payment_date', 'employees.name', 'status', 'amount'];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['benefits', 'deductions', 'employees'];

    public function employees()
    {
        return $this->hasMany('Modules\Payroll\Models\RunPayroll\RunPayrollEmployee', 'run_payroll_id', 'id');
    }

    public function benefits()
    {
        return $this->hasMany('Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeBenefit', 'run_payroll_id', 'id');
    }

    public function deductions()
    {
        return $this->hasMany('Modules\Payroll\Models\RunPayroll\RunPayrollEmployeeDeduction', 'run_payroll_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Models\Setting\Category');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo('App\Models\Banking\Account');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function pay_calendar(): BelongsTo
    {
        return $this->belongsTo('Modules\Payroll\Models\PayCalendar\PayCalendar');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getStatusAttribute($value): string
    {
        $status = $value;

        if (empty($status)) {
            $status = 'not_approved';
        }

        return $status;
    }

    public function getStatusLabelAttribute(): string
    {
        switch ($this->status) {
            case 'approved':
                $status_label = 'label-success';
                break;
            case 'not_approved':
            default:
                $status_label = 'label-danger';
                break;
        }

        return $status_label;
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

    public function delete_attachment()
    {
        if ($attachments = $this->attachment) {
            foreach ($attachments as $file) {
                MediaModel::where('id', $file->id)->delete();
            }
        }
    }

    public static function newFactory(): Factory
    {
        return \Modules\Payroll\Database\Factories\RunPayroll::new();
    }
}
