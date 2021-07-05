<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Payroll\Models\Employee\Employee;

class PayrollV212 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->string('bank_account_number')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Employee::whereNull('bank_account_number')->update(['bank_account_number' => '']);

        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->string('bank_account_number')
                ->nullable(false)
                ->change();
        });
    }
}
