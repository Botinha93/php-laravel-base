<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calors', function (Blueprint $table) {
            $table->string('codigo_catmas')->primary();
            $table->string('desc_catmas')->nullable();
            $table->string('descricao')->nullable();
            $table->integer('idSubcategoria')->nullable();
            $table->integer('idCategoria')->nullable();
            $table->json('contagem_unidades')->nullable();
            $table->json('tags')->nullable();
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
        Schema::dropIfExists('calors');
    }
}
