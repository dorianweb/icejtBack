<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomCreamFlavorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_cream_flavor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('flavor_id');
            $table->foreign('flavor_id')->references('id')->on('flavors');
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
        Schema::dropIfExists('custom_cream_flavor');
    }
}
