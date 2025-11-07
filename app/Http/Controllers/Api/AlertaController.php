<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        return Alerta::with(['region', 'usuario'])->get();
    }

    public function show(Alerta $alerta)
    {
        return $alerta->load(['region', 'usuario']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'nullable|exists:regiones,id',
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'nivel' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $alerta = Alerta::create($data);
        return response($alerta, 201);
    }

    public function update(Request $request, Alerta $alerta)
    {
        $data = $request->validate([
            'region_id' => 'nullable|exists:regiones,id',
            'titulo' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'nivel' => 'nullable|string',
        ]);

        $alerta->update($data);
        return $alerta;
    }

    public function destroy(Alerta $alerta)
    {
        $alerta->delete();
        return response(null, 204);
    }
}
