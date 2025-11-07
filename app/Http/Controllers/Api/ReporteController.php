<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        return Reporte::with(['usuario', 'foto'])->get();
    }

    public function show(Reporte $reporte)
    {
        return $reporte->load(['usuario', 'foto']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'foto_id' => 'nullable|exists:fotosreportes,id',
        ]);

        $reporte = Reporte::create($data);
        return response($reporte, 201);
    }

    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'tipo' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'foto_id' => 'nullable|exists:fotosreportes,id',
            'estado' => 'nullable|string',
        ]);

        $reporte->update($data);
        return $reporte;
    }

    public function destroy(Reporte $reporte)
    {
        $reporte->delete();
        return response(null, 204);
    }
}
