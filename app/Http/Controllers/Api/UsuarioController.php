<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UsuarioService;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
        // protect logout, role update and destructive operations
        $this->middleware('auth:sanctum')->only(['logout','updateRol','update','destroy']);
    }

    public function index()
    {
        return response()->json($this->service->findAll(), 200);
    }

    public function show($id)
    {
        $u = $this->service->findById($id);
        if (!$u) return response()->json(null, 404);
        return response()->json($u, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'correo' => 'sometimes|required|email|unique:Usuarios,correo,' . $id . ',usuario_id',
            'contrasena' => 'nullable|string|min:6',
            'telefono' => 'nullable|string',
            'departamento' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'region' => 'nullable|string',
            'rol' => 'nullable|string|in:admin,usuario',
        ]);

        $updated = $this->service->update($id, $data);
        if (!$updated) return response()->json(['message' => 'Usuario no encontrado'], 404);
        return response()->json($updated, 200);
    }

    public function destroy($id)
    {
        $exists = $this->service->findById($id);
        if (!$exists) return response()->json(null, 404);
        $this->service->deleteById($id);
        return response()->json(null, 204);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:Usuarios,correo',
            'contrasena' => 'required|string|min:6',
            'telefono' => 'nullable|string',
            'departamento' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'region' => 'nullable|string',
        ]);
        $payload = [
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'contrasena' => $data['contrasena'],
            'telefono' => $data['telefono'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'region' => $data['region'] ?? null,
        ];

        $user = $this->service->save($payload);

        // create token (Usuario extends Authenticatable and uses HasApiTokens)
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
        // hide password field name used in Usuarios
        $user->makeHidden(['contrasena']);

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
        $u = Usuario::find($id);
        if (!$u) return response()->json(['message' => 'Usuario no encontrado'], 404);

        $u->rol = $data['rol'];
        $u->save();

        return response()->json(['message' => 'Rol actualizado', 'usuarioId' => $u->usuario_id, 'nuevoRol' => $u->rol], 200);
    }
}
