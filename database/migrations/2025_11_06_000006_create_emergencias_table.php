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
                $table->id();
                $table->foreignId('usuario_id')->constrained('Usuarios', 'usuario_id')->onDelete('cascade');
                $table->text('mensaje');
                $table->double('latitud', 10, 6)->nullable();
                $table->double('longitud', 10, 6)->nullable();
                $table->boolean('atendido')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('emergencias');
    }
};
