<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificaçõesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificações', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo')->nullable();
            $table->string('rota')->nullable();
            $table->string('usuario')->nullable();
            $table->string('unidades')->nullable();
            $table->string('mensagem')->nullable();
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
        Schema::dropIfExists('notificações');
    }
}
