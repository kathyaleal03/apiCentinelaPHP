<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('comentarios')) {
            Schema::create('comentarios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reporte_id')->constrained('reportes')->onDelete('cascade');
                // reference Usuarios table
                $table->unsignedBigInteger('usuario_id');
                $table->foreign('usuario_id')->references('usuario_id')->on('Usuarios')->onDelete('cascade');
                $table->text('mensaje');

            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('comentarios');
    }
};
