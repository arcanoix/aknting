<?php

namespace Modules\CreditDebitNotes\Models;

use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CreditDebitNotes\Database\Factories\DebitNote as DebitNoteFactory;

class DebitNote extends Document
{
    public const TYPE = 'debit-note';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->addCloneableRelation('details');
    }

    protected static function booted()
    {
        static::addGlobalScope(self::TYPE, function (Builder $builder) {
            $builder->where('type', self::TYPE);
        });
    }

    public function details(): HasOne
    {
        return $this->hasOne(DebitNoteDetails::class, 'document_id');
    }

    public function bill(): ?Document
    {
        return Document::withoutGlobalScope(self::TYPE)
            ->bill()
            ->find($this->bill_id);
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'document_id')->where('type', 'debit_note_refund');
    }

    public function getTemplatePathAttribute($value = null)
    {
        return $value ?: 'credit-debit-notes::debit_notes.print';
    }

    public function getPaidAttribute()
    {
        return 0;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status == 'sent') {
            return 'success';
        }

        return parent::getStatusLabelAttribute();
    }

    public function getBillIdAttribute(): ?int
    {
        return $this->details ? $this->details->bill_id : null;
    }

    public function getBillNumberAttribute(): string
    {
        $bill = $this->bill();

        return $bill ? $bill->document_number : '';
    }

    protected static function newFactory(): Factory
    {
        return DebitNoteFactory::new();
    }
}
