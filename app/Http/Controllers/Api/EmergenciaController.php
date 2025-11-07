<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergencia;
use Illuminate\Http\Request;

class EmergenciaController extends Controller
{
    public function index()
    {
        return Emergencia::with('usuario')->get();
    }

    public function show(Emergencia $emergencia)
    {
        return $emergencia->load('usuario');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'mensaje' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $emergencia = Emergencia::create($data);
        return response($emergencia, 201);
    }

    public function update(Request $request, Emergencia $emergencia)
    {
        $data = $request->validate([
            'mensaje' => 'sometimes|required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'atendido' => 'nullable|boolean',
        ]);

        $emergencia->update($data);
        return $emergencia;
    }

    public function destroy(Emergencia $emergencia)
    {
        $emergencia->delete();
        return response(null, 204);
    }
}
