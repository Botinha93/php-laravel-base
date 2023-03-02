<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->json('idMarca')->nullable()->references('id')->on('marcas');
            $table->json('idModelo')->nullable()->references('id')->on('modelos');
            $table->integer('idCategoria')->references('id')->on('categorias');
            $table->json('idTemplate')->nullable()->references('id')->on('templates');
            $table->integer('idSubcategoria')->nullable()->references('id')->on('categorias');
            $table->json('imagem')->nullable();
            $table->integer('tipo');
            $table->json('tags');
            $table->string('codigo_catmas')->nullable();
            $table->text('admType')->nullable();
            $table->text('desc_catmas')->nullable();
            $table->text('descricao')->nullable();
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
        Schema::dropIfExists('items');
    }
}
