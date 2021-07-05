<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollV215 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_run_payroll_employees', function (Blueprint $table) {
            $table->decimal('salary', 15, 2)->change();
            $table->decimal('benefit', 15, 2)->change();
            $table->decimal('deduction', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_run_payroll_employees', function (Blueprint $table) {
            $table->integer('salary')->change();
            $table->integer('benefit')->change();
            $table->integer('deduction')->change();
            $table->integer('total')->change();
        });
    }
}
