<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UsuarioService;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    protected $service;

    public function __construct(UsuarioService $service)
    {
        $this->service = $service;
    }


     public function index()
    {
        $usuarios = Usuario::all();

        return response()->json($usuarios, 200);
    }

    public function register(Request $request)
    {

        try {

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:usuarios,correo', 
                'contrasena' => 'required|string', 
                'telefono' => 'nullable|string', 
                'departamento' => 'nullable|string', 
                'ciudad' => 'nullable|string', 
                'region' => 'nullable|integer', 
            ]);

            $payload = [
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'contrasena' => Hash::make($data['contrasena']), 
                'telefono' => $data['telefono'] ?? null,
                'departamento' => $data['departamento'] ?? null,
                'ciudad' => $data['ciudad'] ?? null,
                'region' => $data['region'] ?? null, 
            ];

            $user = $this->service->save($payload);
            $user->makeHidden(['contrasena']);
            return response()->json([
                'user' => $user,
                'usuarioId' => $user->usuario_id
            ], 201);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
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

        $user->makeHidden(['contrasena']);

        return response()->json([
            'user' => $user,
            'usuarioId' => $user->usuario_id,
            'rol' => $user->rol
        ], 200);
    }

    public function logout(Request $request)
    {
        
        return response()->json(['message' => 'Logout exitoso'], 200);
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

        return response()->json(['message' => 'Rol actualizado', 'usuarioId' => $u->id, 'nuevoRol' => $u->rol], 200);
    }
}
