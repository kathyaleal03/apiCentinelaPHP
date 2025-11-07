<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function index()
    {
        return Comentario::with(['reporte', 'usuario'])->get();
    }

    public function show(Comentario $comentario)
    {
        return $comentario->load(['reporte', 'usuario']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reporte_id' => 'required|exists:reportes,id',
            'user_id' => 'required|exists:users,id',
            'mensaje' => 'required|string',
        ]);

        $comentario = Comentario::create($data);
        return response($comentario, 201);
    }

    public function update(Request $request, Comentario $comentario)
    {
        $data = $request->validate([
            'mensaje' => 'required|string',
        ]);

        $comentario->update($data);
        return $comentario;
    }

    public function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return response(null, 204);
    }
}
