<?php

namespace Modules\CreditDebitNotes\Models;

use App\Abstracts\Model;

class CreditNoteDetails extends Model
{
    protected $table = 'credit_debit_notes_credit_note_details';

    protected $fillable = [
        'company_id',
        'document_id',
        'invoice_id',
        'credit_customer_account',
    ];
}
