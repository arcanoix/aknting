<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditDebitNotesV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Credit notes
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('credit_note_number');
            $table->string('status');
            $table->dateTime('issued_at');
            $table->integer('invoice_id')->nullable();
            $table->double('amount', 15, 4);
            $table->boolean('credit_customer_account')->default(false);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('category_id')->default(1);
            $table->integer('contact_id');
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->string('contact_tax_number')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('invoice_id');
            $table->unique(['company_id', 'credit_note_number', 'deleted_at'], 'credit_notes_company_number_deleted_unique');
        });

        Schema::create('credit_note_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('credit_note_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('credit_note_id');
        });

        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('credit_note_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->double('tax', 15, 4)->default('0.0000');
            $table->double('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('credit_note_id');
        });

        Schema::create('credit_note_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('credit_note_id');
            $table->bigInteger('credit_note_item_id');
            $table->integer('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('credit_note_id');
        });

        Schema::create('credit_note_totals', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('credit_note_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('credit_note_id');
        });

        // Debit notes
        Schema::create('debit_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('debit_note_number');
            $table->string('status');
            $table->dateTime('issued_at');
            $table->integer('bill_id')->nullable();
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('category_id')->default(1);
            $table->integer('contact_id');
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->string('contact_tax_number')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('bill_id');
            $table->unique(['company_id', 'debit_note_number', 'deleted_at'], 'debit_notes_company_number_deleted_unique');
        });

        Schema::create('debit_note_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('debit_note_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('debit_note_id');
        });

        Schema::create('debit_note_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('debit_note_id');
            $table->integer('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->double('quantity', 7, 2);
            $table->double('price', 15, 4);
            $table->double('total', 15, 4);
            $table->double('tax', 15, 4)->default('0.0000');
            $table->double('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('debit_note_id');
        });

        Schema::create('debit_note_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('debit_note_id');
            $table->bigInteger('debit_note_item_id');
            $table->integer('tax_id');
            $table->string('name');
            $table->double('amount', 15, 4)->default('0.0000');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('debit_note_id');
        });

        Schema::create('debit_note_totals', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->bigInteger('debit_note_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->double('amount', 15, 4);
            $table->integer('sort_order');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('debit_note_id');
        });

        // Credits transactions
        Schema::create('credits_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('type');
            $table->double('amount', 15, 4);
            $table->string('currency_code', 3);
            $table->double('currency_rate', 15, 8);
            $table->integer('document_id');
            $table->integer('contact_id');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index('contact_id');
            $table->index('document_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('credit_note_histories');
        Schema::dropIfExists('credit_note_items');
        Schema::dropIfExists('credit_note_item_taxes');
        Schema::dropIfExists('credit_note_totals');
        Schema::dropIfExists('debit_notes');
        Schema::dropIfExists('debit_note_histories');
        Schema::dropIfExists('debit_note_items');
        Schema::dropIfExists('debit_note_item_taxes');
        Schema::dropIfExists('debit_note_totals');
        Schema::dropIfExists('credits_transactions');
    }
}
