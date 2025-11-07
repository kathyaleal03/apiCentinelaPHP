<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UsuarioService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
        // protect logout and role update
        $this->middleware('auth:sanctum')->only(['logout','updateRol']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,email',
            'contrasena' => 'required|string|min:6',
            'telefono' => 'nullable|string',
            'departamento' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        // map to Laravel fields
        $payload = [
            'name' => $data['nombre'],
            'email' => $data['correo'],
            'password' => $data['contrasena'],
            'telefono' => $data['telefono'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'region' => $data['region'] ?? null,
        ];

        $user = $this->service->save($payload);

        // create token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $user = $this->service->authenticate($data['correo'], $data['contrasena']);
        if (!$user) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        // hide password
        $user->makeHidden(['password']);

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(null, 204);
    }

    public function updateRol(Request $request, $id)
    {
        $data = $request->validate([
            'rol' => 'required|string|in:admin,usuario',
        ]);

        $u = User::find($id);
        if (!$u) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $u->rol = $data['rol'];
        $u->save();

        return response()->json(['message' => 'Rol actualizado', 'usuarioId' => $u->id, 'nuevoRol' => $u->rol], 200);
    }
}
