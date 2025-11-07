<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioService
{
    public function findAll()
    {
        return User::all();
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function findByCorreo($correo)
    {
        return User::where('correo', $correo)
            ->orWhere('email', $correo)
            ->first();
    }

    protected function normalizeUserPayload(array $data): array
    {
        // map Java-style fields to Laravel default where reasonable
        if (isset($data['contrasena'])) {
            $data['password'] = $data['contrasena'];
            unset($data['contrasena']);
        }
        if (isset($data['correo'])) {
            $data['email'] = $data['correo'];
            unset($data['correo']);
        }
        if (isset($data['nombre'])) {
            $data['name'] = $data['nombre'];
            unset($data['nombre']);
        }
        return $data;
    }

    public function save(array $data)
    {
        $data = $this->normalizeUserPayload($data);

        if (isset($data['password'])) {
            $pw = $data['password'];
            if (!Str::startsWith($pw, ['$2y$', '$2a$', '$2b$'])) {
                $data['password'] = Hash::make($pw);
            }
        }

        return User::create($data);
    }

    public function deleteById($id)
    {
        return User::destroy($id);
    }

    public function update($id, array $data)
    {
        $u = User::find($id);
        if (!$u) return null;

        $data = $this->normalizeUserPayload($data);

        if (isset($data['password'])) {
            $pw = $data['password'];
            if (!Str::startsWith($pw, ['$2y$', '$2a$', '$2b$'])) {
                $data['password'] = Hash::make($pw);
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
        $stored = $u->password ?? null;
        if (!$stored) return null;

        if (Str::startsWith($stored, ['$2y$', '$2a$', '$2b$'])) {
            return Hash::check($contrasena, $stored) ? $u : null;
        }

        // stored in plaintext (legacy): compare and upgrade
        if ($stored === $contrasena) {
            $u->password = Hash::make($contrasena);
            $u->save();
            return $u;
        }

        return null;
    }
}
