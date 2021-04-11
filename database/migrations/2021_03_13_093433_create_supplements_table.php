<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->float('weight');
            $table->string('unit');
            $table->unsignedBigInteger('supplement_type_id');
            $table->foreign('supplement_type_id')->references('id')->on('supplement_types');
            $table->softDeletes();
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
        Schema::table('supplements', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIfExists();
        });
    }
}
