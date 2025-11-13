<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('alertas')) {
            Schema::create('alertas', function (Blueprint $table) {
                $table->bigIncrements('alerta_id');
                $table->unsignedBigInteger('region_id')->nullable();
                $table->foreign('region_id')->references('region_id')->on('regiones')->nullOnDelete();
                $table->string('titulo');
                $table->text('descripcion')->nullable();
                $table->string('nivel')->default('Verde');
                $table->unsignedBigInteger('id_usuario');
                $table->foreign('id_usuario')->references('usuario_id')->on('Usuarios')->onDelete('cascade');

            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
};
