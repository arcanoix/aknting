<?php

namespace Modules\CreditDebitNotes\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version110 extends Listener
{
    const ALIAS = 'credit-debit-notes';

    const VERSION = '1.1.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();
        $this->updateSettings();
        $this->copyCreditsTransactions();
        $this->copyCreditNotesDetails();
        $this->copyDebitNotesDetails();
    }

    public function updateDatabase()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);
    }

    public function updateSettings()
    {
        setting()->setExtraColumns(['company_id' => company_id()]);
        setting()->forgetAll();
        setting()->load(true);

        $old_keys = [
            'credit_note.color',
            'credit_note.item_name',
            'credit_note.number_digit',
            'credit_note.number_next',
            'credit_note.number_prefix',
            'credit_note.price_name',
            'credit_note.quantity_name',
            'credit_note.template',
            'credit_note.title',
            'debit_note.number_digit',
            'debit_note.number_next',
            'debit_note.number_prefix',
        ];

        foreach ($old_keys as $key) {
            if (setting()->has($key)) {
                setting()->set(self::ALIAS . '.' . $key, setting($key));
                setting()->forget($key);
            }
        }

        setting()->save();
    }

    public function copyCreditsTransactions()
    {
        if (!Schema::hasTable('credits_transactions')) {
            return;
        }

        $columns = [
            'company_id',
            'document_id',
            'type',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'category_id',
            'contact_id',
            'description',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $offset = 0;
        $limit = 500000;
        $builder = DB::table('credits_transactions')
            ->where('company_id', company_id())
            ->select($columns)
            ->limit($limit)
            ->offset($offset);

        while ($builder->cursor()->count()) {
            DB::table('credit_debit_notes_credits_transactions')
                ->insertUsing($columns, $builder);

            $offset += $limit;
            $builder->limit($limit)->offset($offset);
        }
    }

    public function copyCreditNotesDetails()
    {
        if (!Schema::hasTable('credit_notes_v20')) {
            return;
        }

        $credit_notes_v20 = DB::table('credit_notes_v20')
            ->where('company_id', company_id())
            ->cursor();

        foreach ($credit_notes_v20 as $credit_note_v20) {
            $document_id = DB::table('documents')->where('type', 'credit-note')
                ->where([
                    'company_id'      => $credit_note_v20->company_id,
                    'document_number' => $credit_note_v20->credit_note_number,
                    'deleted_at'      => $credit_note_v20->deleted_at,
                ])
                ->pluck('id')
                ->first();

            $invoice_id = null;
            if ($credit_note_v20->invoice_id) {
                $invoice_v20 = DB::table('invoices_v20')->where('id', $credit_note_v20->invoice_id)->first();
                $invoice_id = DB::table('documents')->where('type', 'invoice')
                    ->where([
                        'company_id'      => $invoice_v20->company_id,
                        'document_number' => $invoice_v20->invoice_number,
                        'deleted_at'      => $invoice_v20->deleted_at,
                    ])
                    ->pluck('id')
                    ->first();
            }

            DB::table('credit_debit_notes_credit_note_details')
                ->insert([
                    'company_id'              => $credit_note_v20->company_id,
                    'document_id'             => $document_id,
                    'invoice_id'              => $invoice_id,
                    'credit_customer_account' => $credit_note_v20->credit_customer_account,
                    'created_at'              => $credit_note_v20->created_at,
                    'updated_at'              => $credit_note_v20->updated_at,
                    'deleted_at'              => $credit_note_v20->deleted_at,
                ]);
        }
    }

    public function copyDebitNotesDetails()
    {
        if (!Schema::hasTable('debit_notes_v20')) {
            return;
        }

        $debit_notes_v20 = DB::table('debit_notes_v20')
            ->where('company_id', company_id())
            ->cursor();

        foreach ($debit_notes_v20 as $debit_note_v20) {
            $document_id = DB::table('documents')->where('type', 'debit-note')
                ->where([
                    'company_id'      => $debit_note_v20->company_id,
                    'document_number' => $debit_note_v20->debit_note_number,
                    'deleted_at'      => $debit_note_v20->deleted_at,
                ])
                ->pluck('id')
                ->first();

            $bill_id = null;
            if ($debit_note_v20->bill_id) {
                $bill_v20 = DB::table('bills_v20')->where('id', $debit_note_v20->bill_id)->first();
                $bill_id = DB::table('documents')->where('type', 'bill')
                    ->where([
                        'company_id'      => $bill_v20->company_id,
                        'document_number' => $bill_v20->bill_number,
                        'deleted_at'      => $bill_v20->deleted_at,
                    ])
                    ->pluck('id')
                    ->first();
            }

            DB::table('credit_debit_notes_debit_note_details')
                ->insert([
                    'company_id'  => $debit_note_v20->company_id,
                    'document_id' => $document_id,
                    'bill_id'     => $bill_id,
                    'created_at'  => $debit_note_v20->created_at,
                    'updated_at'  => $debit_note_v20->updated_at,
                    'deleted_at'  => $debit_note_v20->deleted_at,
                ]);
        }
    }
}
