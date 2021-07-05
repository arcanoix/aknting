<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PayrollV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('contact_id');
            $table->date('birth_day');
            $table->string('gender');
            $table->integer('position_id')->default();
            $table->double('amount', 15, 4);
            $table->double('currency_rate', 15, 8)->nullable();
            $table->date('hired_at');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'contact_id']);
            $table->unique(['company_id', 'contact_id', 'deleted_at']);
        });

        Schema::create('payroll_employee_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('employee_id');
            $table->string('type');
            $table->double('amount');
            $table->string('currency_code');
            $table->string('recurring')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('not_approved');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_employee_deductions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('employee_id');
            $table->string('type');
            $table->double('amount');
            $table->string('currency_code');
            $table->string('recurring')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('not_approved');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'name', 'deleted_at']);
        });

        Schema::create('payroll_run_payroll_employee_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('employee_id');
            $table->integer('employee_benefit_id')->nullable();
            $table->integer('pay_calendar_id');
            $table->integer('run_payroll_id');
            $table->string('type');
            $table->double('amount');
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_run_payroll_employee_deductions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('employee_id');
            $table->integer('employee_deduction_id')->nullable();
            $table->integer('pay_calendar_id');
            $table->integer('run_payroll_id');
            $table->string('type');
            $table->double('amount');
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_run_payroll_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('employee_id');
            $table->integer('pay_calendar_id');
            $table->integer('run_payroll_id');
            $table->integer('salary');
            $table->integer('benefit');
            $table->integer('deduction');
            $table->integer('total');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_run_payrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->date('from_date');
            $table->date('to_date');
            $table->date('payment_date');
            $table->integer('payment_id')->nullable();
            $table->integer('pay_calendar_id');
            $table->integer('category_id');
            $table->integer('account_id');
            $table->string('payment_method');
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->double('amount', 15, 4);
            $table->string('status')->default('not_approved');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'name', 'deleted_at']);
        });

        Schema::create('payroll_pay_calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name');
            $table->string('type');
            $table->string('pay_day_mode');
            $table->string('pay_day')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_pay_calendar_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('pay_calendar_id');
            $table->integer('employee_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('payroll_setting_pay_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('pay_type');
            $table->string('pay_item');
            $table->string('amount_type');
            $table->string('code')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payroll_employees');
        Schema::drop('payroll_employee_benefits');
        Schema::drop('payroll_employee_deductions');
        Schema::drop('payroll_positions');
        Schema::drop('payroll_run_payroll_employee_benefits');
        Schema::drop('payroll_run_payroll_employee_deductions');
        Schema::drop('payroll_run_payroll_employees');
        Schema::drop('payroll_run_payrolls');
        Schema::drop('payroll_pay_calendars');
        Schema::drop('payroll_pay_calendar_employees');
        Schema::drop('payroll_setting_pay_items');
    }
}
