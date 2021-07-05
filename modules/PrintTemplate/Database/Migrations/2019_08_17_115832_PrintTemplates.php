<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrintTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_templates', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('company_id');
            $table->text('type');
            $table->text('pagesize');
            $table->text('name');
            $table->boolean('enabled');
            $table->timestamps();
            $table->softDeletes(); 
            
        });

        Schema::create('print_template_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('template_id');
            $table->integer('item_id');
            $table->text('attr');  //xCoord - yCoord - Width - Height - FontSize - FontType - FontWeight - FontStyle -- JSON ENCODED 
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
        //

        Schema::drop('print_templates');
        Schema::drop('print_template_item');
    }
}
