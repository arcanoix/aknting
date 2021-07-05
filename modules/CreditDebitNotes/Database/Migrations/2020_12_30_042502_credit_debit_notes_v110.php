<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditDebitNotesV110 extends Migration
{
    private $tablePrefix;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->tablePrefix = Schema::getConnection()->getTablePrefix();

        Schema::create('credit_debit_notes_credit_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('invoice_id')->nullable();
            $table->boolean('credit_customer_account')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('document_id');
            $table->index('invoice_id');
            $table->unique(['company_id', 'document_id', 'invoice_id', 'deleted_at'], $this->tablePrefix.'cdn_credit_note_details_unique');
        });

        Schema::create('credit_debit_notes_debit_note_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('document_id');
            $table->unsignedInteger('bill_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('document_id');
            $table->index('bill_id');
            $table->unique(['company_id', 'document_id', 'bill_id', 'deleted_at'], $this->tablePrefix.'cdn_debit_note_details_unique');
        });

        Schema::create('credit_debit_notes_credits_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('document_id');
            $table->string('type');
            $table->dateTime('paid_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code', 3);
            $table->double('currency_rate', 15, 8);
            $table->unsignedInteger('category_id')->default(1);
            $table->unsignedInteger('contact_id');
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type'],  $this->tablePrefix.'cdn_credits_transactions_company_id_type_index');
            $table->index('contact_id');
            $table->index('document_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_debit_notes_credit_note_details');
        Schema::dropIfExists('credit_debit_notes_debit_note_details');
        Schema::dropIfExists('credit_debit_notes_credits_transactions');
    }
}
