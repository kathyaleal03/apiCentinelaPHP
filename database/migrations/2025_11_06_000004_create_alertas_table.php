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
                $table->id();
                $table->foreignId('region_id')->nullable()->constrained('regiones')->nullOnDelete();
                $table->string('titulo');
                $table->text('descripcion')->nullable();
                $table->string('nivel')->default('Verde');
                $table->foreignId('usuario_id')->constrained('Usuarios', 'usuario_id')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
};
