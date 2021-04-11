<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCreamSupplementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_cream_supplement', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('supplement_id');
            $table->foreign('supplement_id')->references('id')->on('supplements');
            $table->unsignedBigInteger('custom_cream_id');
            $table->foreign('custom_cream_id')->references('id')->on('custom_creams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_cream_supplement');
    }
}
