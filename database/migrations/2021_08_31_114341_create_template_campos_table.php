<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_campos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idTemplate')->references('id')->on('templates');
            $table->string('nome');
            $table->integer('imput');
            $table->text('op');
            $table->text('op_aux')->nullable();
            $table->boolean('obrigatorio');
            $table->text('ajuda')->nullable();
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
        Schema::dropIfExists('template_campos');
    }
}
