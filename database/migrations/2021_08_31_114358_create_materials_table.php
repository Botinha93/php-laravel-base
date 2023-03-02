<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idItem')->references('id')->on('items');
            $table->json('patrimonio_serie')->nullable();
            $table->date('dataFabr')->nullable();
            $table->date('dataVal')->nullable();
            $table->integer('estConservacao')->nullable();
            $table->double('quantidade')->nullable()->default(1);
            $table->double('demanda')->nullable();
            $table->string('unidade')->nullable();
            $table->json('campos')->nullable();
            //$table->json('disponibilizado')->default('{"disp": false , "quantidade": 0, "patrimonio":""}');            
            $table->json('disponibilizado')->nullable(false);            
            $table->boolean('ativo')->default(true);;
            $table->boolean('movimentando')->default(false);
            $table->boolean('vinculado')->default(false);
            $table->string('usuariovinculado')->nullable();
            $table->string('admType')->nullable(); 
            $table->string('status_material')->nullable();
            $table->json('marca');
            $table->json('modelo');
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
        Schema::dropIfExists('materials');
    }
}
