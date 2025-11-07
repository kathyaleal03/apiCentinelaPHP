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
                $table->foreignId('usuario_id')->constrained('Usuarios', 'usuario_id')->onDelete('cascade');
                $table->text('mensaje');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('comentarios');
    }
};
