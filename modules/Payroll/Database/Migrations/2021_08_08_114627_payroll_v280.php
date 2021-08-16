<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollV280 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employee_benefits', function (Blueprint $table) {
            $table->tinyInteger('from_date')
                ->nullable()
                ->after('description');
            $table->tinyInteger('to_date')
                ->nullable()
                ->after('from_date');
        });

        Schema::table('payroll_employee_deductions', function (Blueprint $table) {
            $table->tinyInteger('from_date')
                ->nullable()
                ->after('description');
            $table->tinyInteger('to_date')
                ->nullable()
                ->after('from_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_employee_benefits', function (Blueprint $table) {
            $table->dropColumn(['from_date', 'to_date']);
        });

        Schema::table('payroll_employee_deductions', function (Blueprint $table) {
            $table->dropColumn(['from_date', 'to_date']);
        });
    }
}
