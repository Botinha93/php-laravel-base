<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatepermutaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permutas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('materialID')->references('id')->on('items');
            $table->string('troca')->nullable();
            $table->string('tamanhoPossuido')->nullable();
            $table->string('usuario')->nullable();
            $table->boolean('aprovado')->default(false);
            $table->boolean('aprovadoRecebedor')->default(false);
            $table->integer('permutaID')->references('id')->on('permutas')->nullable();
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
        Schema::dropIfExists('permutas');
    }
}
