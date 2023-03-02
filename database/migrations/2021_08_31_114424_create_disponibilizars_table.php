<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilizarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disponibilizars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idMaterial')->references('id')->on('materials');
            $table->boolean('aprovado');
            $table->double('quantidade')->nullable();
            $table->string('patrimonio')->nullable();
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
        Schema::dropIfExists('disponibilizars');
    }
}
