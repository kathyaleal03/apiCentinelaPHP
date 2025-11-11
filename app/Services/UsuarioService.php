<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioService
{
    public function findAll()
    {
        return Usuario::all();
    }

    public function findById($id)
    {
        return Usuario::find($id);
    }

    public function findByCorreo($correo)
    {
        
        return Usuario::where('correo', $correo)->first();
    }

    protected function normalizeUserPayload(array $data): array
    {
        
        if (isset($data['contrasena'])) {
            $data['contraseña'] = $data['contrasena'];
            unset($data['password']);
        }
        if (isset($data['correo'])) {
            $data['correo'] = $data['correo'];
            unset($data['correo']);
        }
        if (isset($data['nombre'])) {
            $data['nombre'] = $data['nombre'];
            unset($data['nombre']);
        }
        return $data;
    }

    public function save(array $data)
    {
        $data = $this->normalizeUserPayload($data);

        if (isset($data['contrasena'])) {
            $pw = $data['contrasena'];
            if (!Str::startsWith($pw, ['$2y$', '$2a$', '$2b$'])) {
                $data['contrasena'] = Hash::make($pw);
            }
        }

        return Usuario::create($data);
    }

    public function deleteById($id)
    {
        return Usuario::destroy($id);
    }

    public function update($id, array $data)
    {
    $u = Usuario::find($id);
        if (!$u) return null;

        $data = $this->normalizeUserPayload($data);

        if (isset($data['contrasena'])) {
            $pw = $data['contrasena'];
            if (!Str::startsWith($pw, ['$2y$', '$2a$', '$2b$'])) {
                $data['contrasena'] = Hash::make($pw);
            }
        }

        $u->fill($data);
        $u->save();
        return $u;
    }

    public function authenticate(string $correo, string $contrasena)
    {
        $u = $this->findByCorreo($correo);
        if (!$u) return null;
        $stored = $u->contrasena ?? null;
        if (!$stored) return null;

        if (Str::startsWith($stored, ['$2y$', '$2a$', '$2b$'])) {
            return Hash::check($contrasena, $stored) ? $u : null;
        }

        
        if ($stored === $contrasena) {
            $u->contrasena = Hash::make($contrasena);
            $u->save();
            return $u;
        }

        return null;
    }
}
