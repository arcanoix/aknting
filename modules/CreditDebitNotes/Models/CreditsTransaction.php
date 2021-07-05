<?php

namespace Modules\CreditDebitNotes\Models;

use App\Abstracts\Model;
use App\Traits\Currencies;
use App\Traits\DateTime;
use Illuminate\Database\Eloquent\Builder;

class CreditsTransaction extends Model
{
    use Currencies, DateTime;

    protected $table = 'credit_debit_notes_credits_transactions';

    protected $dates = ['deleted_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'type',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'document_id',
        'contact_id',
        'category_id',
        'description',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['amount'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice', 'document_id');
    }

    public function credit_note()
    {
        return $this->belongsTo('Modules\CreditDebitNotes\Models\CreditNote', 'document_id');
    }

    /**
     * Scope to include only income.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIncome($query)
    {
        return $query->where($this->table . '.type', '=', 'income');
    }

    /**
     * Scope to include only expense.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpense($query)
    {
        return $query->where($this->table . '.type', '=', 'expense');
    }

    /**
     * Get by document (invoice/credit note).
     *
     * @param Builder $query
     * @param  integer $document_id
     * @return Builder
     */
    public function scopeDocument($query, $document_id)
    {
        return $query->where('document_id', '=', $document_id);
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }
}
