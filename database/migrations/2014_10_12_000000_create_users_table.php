<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('idKeycloak');
            $table->string('usuario')->nullable();
            $table->string('Unidade')->nullable();
            $table->json('permissions')->nullable();
            $table->json('tempPermissions')->default(DB::raw('(JSON_ARRAY())'));
            $table->boolean('aprovado')->default(0);
            $table->string('justificativa')->nullable();
            $table->date('inicio')->nullable();
            $table->date('fim')->nullable();
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
        Schema::dropIfExists('users');
    }
}
