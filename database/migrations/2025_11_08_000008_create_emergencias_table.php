<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('emergencias')) {
            Schema::create('emergencias', function (Blueprint $table) {
                $table->bigIncrements('emergencia_id');
                $table->unsignedBigInteger('usuario_id');
                $table->foreign('usuario_id')->references('usuario_id')->on('Usuarios')->onDelete('cascade');
                $table->text('mensaje');
                $table->double('latitud', 10, 6)->nullable();
                $table->double('longitud', 10, 6)->nullable();
                $table->boolean('atendido')->default(false);

            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('emergencias');
    }
};
