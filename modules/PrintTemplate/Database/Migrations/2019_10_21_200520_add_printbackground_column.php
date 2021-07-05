<?php

use Illuminate\Database\Migrations\Migration;

class AddPrintBackgroundColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('print_templates', function ($table) {
            $table->boolean('printBackground')->nullable()->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('print_templates', function ($table) {
            $table->dropColumn('printBackground');
		});
    }
}
