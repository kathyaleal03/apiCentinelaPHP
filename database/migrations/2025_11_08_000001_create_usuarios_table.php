<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('usuario_id');
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('contrasena');
            $table->string('telefono')->nullable();
            $table->string('departamento')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('region')->nullable();
            $table->string('rol')->default('usuario');
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
