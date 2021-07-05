<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreditDebitNotesV105 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Credits transactions
        Schema::table('credits_transactions', function (Blueprint $table) {
            $table->dateTime('paid_at')
                ->nullable()
                ->after('type');
            $table->integer('category_id')
                ->default(1)
                ->after('contact_id');

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
        Schema::table('credits_transactions', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
        });

        Schema::table('credits_transactions', function (Blueprint $table) {
            $table->dropColumn(['paid_at', 'category_id']);
        });
    }
}
