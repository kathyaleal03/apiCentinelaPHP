<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1) Copy existing users into Usuarios (if any)
        $users = DB::table('users')->get();
        $map = [];
        foreach ($users as $u) {
            $insertId = DB::table('Usuarios')->insertGetId([
                'nombre' => $u->name ?? ($u->nombre ?? 'Usuario'),
                'correo' => $u->email ?? ($u->correo ?? null),
                'contrasena' => $u->password ?? ($u->contrasena ?? ''),
                'telefono' => $u->telefono ?? null,
                'departamento' => $u->departamento ?? null,
                'ciudad' => $u->ciudad ?? null,
                'region' => $u->region ?? null,
                'rol' => $u->rol ?? 'usuario',
                'created_at' => $u->created_at ?? now(),
                'updated_at' => $u->updated_at ?? now(),
            ]);
            $map[$u->id] = $insertId;
        }

        // 2) Add usuario_id to related tables
        Schema::table('reportes', function (Blueprint $table) {
            if (!Schema::hasColumn('reportes', 'usuario_id')) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('user_id');
            }
        });

        Schema::table('alertas', function (Blueprint $table) {
            if (!Schema::hasColumn('alertas', 'usuario_id')) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('user_id');
            }
        });

        Schema::table('comentarios', function (Blueprint $table) {
            if (!Schema::hasColumn('comentarios', 'usuario_id')) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('user_id');
            }
        });

        Schema::table('emergencias', function (Blueprint $table) {
            if (!Schema::hasColumn('emergencias', 'usuario_id')) {
                $table->unsignedBigInteger('usuario_id')->nullable()->after('user_id');
            }
        });

        // 3) Populate usuario_id using the map (if users existed)
        if (!empty($map)) {
            foreach ($map as $oldId => $newId) {
                DB::table('reportes')->where('user_id', $oldId)->update(['usuario_id' => $newId]);
                DB::table('alertas')->where('user_id', $oldId)->update(['usuario_id' => $newId]);
                DB::table('comentarios')->where('user_id', $oldId)->update(['usuario_id' => $newId]);
                DB::table('emergencias')->where('user_id', $oldId)->update(['usuario_id' => $newId]);
            }
        }

        // 4) Create foreign key constraints and drop old user_id columns

    }

    public function down()
    {
        // Attempt to reverse: add user_id back as nullable (no data restore)
        Schema::table('reportes', function (Blueprint $table) {
            if (!Schema::hasColumn('reportes', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('usuario_id');
            }
            try { $table->dropForeign(['usuario_id']); } catch (\Exception $e) {}
            $table->dropColumn('usuario_id');
        });

        Schema::table('alertas', function (Blueprint $table) {
            if (!Schema::hasColumn('alertas', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('usuario_id');
            }
            try { $table->dropForeign(['usuario_id']); } catch (\Exception $e) {}
            $table->dropColumn('usuario_id');
        });

        Schema::table('comentarios', function (Blueprint $table) {
            if (!Schema::hasColumn('comentarios', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('usuario_id');
            }
            try { $table->dropForeign(['usuario_id']); } catch (\Exception $e) {}
            $table->dropColumn('usuario_id');
        });

        Schema::table('emergencias', function (Blueprint $table) {
            if (!Schema::hasColumn('emergencias', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('usuario_id');
            }
            try { $table->dropForeign(['usuario_id']); } catch (\Exception $e) {}
            $table->dropColumn('usuario_id');
        });

        Schema::dropIfExists('Usuarios');
    }
};
