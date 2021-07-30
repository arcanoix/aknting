<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PosV170 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_barcodes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('item_id');
            $table->string('code');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_barcodes');
    }
}
