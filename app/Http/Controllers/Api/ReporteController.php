<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use Illuminate\Http\Request;
use App\Services\ReporteService;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
        $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }
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
        // delegate to service which handles foto creation and usuario resolution
        $request->validate([
            'usuario_id' => 'required|exists:Usuarios,usuario_id',
            'tipo' => 'sometimes|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'fotoUrl' => 'nullable|url',
            'foto_url' => 'nullable|url',
        ]);

        $reporte = $this->reporteService->createFromRequest($request);
        return response($reporte, 201);
    }

    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'tipo' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            // fotosreportes uses foto_id as primary key in the DB
            'foto_id' => 'nullable|exists:fotosreportes,foto_id',
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
