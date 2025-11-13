<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dateTime('fecha_hora')->nullable();
        });
    }
    public function down() {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn('fecha_hora');
        });
    }
};
