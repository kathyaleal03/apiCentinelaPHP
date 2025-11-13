<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up() {
        // Actualizar todos los reportes existentes que no tienen fecha_hora
        // Les ponemos una fecha aproximada (puedes ajustarla)
        DB::table('reportes')
            ->whereNull('fecha_hora')
            ->update(['fecha_hora' => now()]);
    }
    
    public function down() {
        // No hacer nada en rollback
    }
};
