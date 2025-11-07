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
                $table->id();
                // reference Usuarios table with custom primary key
                $table->foreignId('usuario_id')->constrained('Usuarios', 'usuario_id')->onDelete('cascade');
                $table->string('tipo');
                $table->text('descripcion')->nullable();
                $table->double('latitud', 10, 6)->nullable();
                $table->double('longitud', 10, 6)->nullable();
                $table->foreignId('foto_id')->nullable()->constrained('fotosreportes');
                $table->string('estado')->default('Activo');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
