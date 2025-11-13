<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Esta migración ya no es necesaria porque las tablas se crean directamente
        // con las columnas correctas (usuario_id, id_usuario) en sus migraciones originales.
        // Solo verificamos y limpiamos si existiera alguna columna 'user_id' residual.
        
        // Eliminar columna user_id de reportes si existe
        if (Schema::hasTable('reportes') && Schema::hasColumn('reportes', 'user_id')) {
            Schema::table('reportes', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignorar si no existe foreign key
                }
                $table->dropColumn('user_id');
            });
        }

        // Eliminar columna user_id de alertas si existe
        if (Schema::hasTable('alertas') && Schema::hasColumn('alertas', 'user_id')) {
            Schema::table('alertas', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignorar si no existe foreign key
                }
                $table->dropColumn('user_id');
            });
        }

        // Eliminar columna user_id de comentarios si existe
        if (Schema::hasTable('comentarios') && Schema::hasColumn('comentarios', 'user_id')) {
            Schema::table('comentarios', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignorar si no existe foreign key
                }
                $table->dropColumn('user_id');
            });
        }

        // Eliminar columna user_id de emergencias si existe
        if (Schema::hasTable('emergencias') && Schema::hasColumn('emergencias', 'user_id')) {
            Schema::table('emergencias', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignorar si no existe foreign key
                }
                $table->dropColumn('user_id');
            });
        }
    }

    public function down()
    {
        // No hacer nada en el rollback ya que las tablas originales
        // manejan sus propias columnas en sus respectivas migraciones
    }
};
