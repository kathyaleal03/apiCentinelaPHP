<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('reportes')) {
            Schema::create('reportes', function (Blueprint $table) {
                $table->bigIncrements('reporte_id');
                // reference Usuarios table with custom primary key
                $table->unsignedBigInteger('usuario_id');
                $table->foreign('usuario_id')->references('usuario_id')->on('Usuarios')->onDelete('cascade');
                $table->string('tipo');
                $table->text('descripcion')->nullable();
                $table->double('latitud', 10, 6)->nullable();
                $table->double('longitud', 10, 6)->nullable();
                $table->unsignedBigInteger('foto_id')->nullable();
                $table->foreign('foto_id')->references('foto_id')->on('fotosreportes')->onDelete('set null');
                $table->string('estado')->default('Activo');

            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
