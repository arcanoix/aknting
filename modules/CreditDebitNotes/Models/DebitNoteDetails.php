<?php

namespace Modules\CreditDebitNotes\Models;

use App\Abstracts\Model;

class DebitNoteDetails extends Model
{
    protected $table = 'credit_debit_notes_debit_note_details';

    protected $fillable = [
        'company_id',
        'document_id',
        'bill_id',
    ];
}
