<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmployeesV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_employees', function (Blueprint $table) {
            $table->id('id');
            $table->integer('company_id');
            $table->integer('contact_id');
            $table->date('birth_day');
            $table->string('gender');
            $table->integer('position_id')->default();
            $table->double('amount', 15, 4);
            $table->date('hired_at');
            $table->string('bank_account_number')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'contact_id']);
            $table->unique(['company_id', 'contact_id', 'deleted_at']);
        });

        Schema::create('employees_positions', function (Blueprint $table) {
            $table->id('id');
            $table->integer('company_id');
            $table->string('name');
            $table->boolean('enabled');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'name', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees_employees');
        Schema::drop('employees_positions');
    }
}
