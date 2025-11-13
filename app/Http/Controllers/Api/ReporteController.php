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
    }

    public function index()
    {
        $reportes = Reporte::with(['usuario', 'foto'])->get();
        $reportes->transform(function ($reporte) {
            $reporte->fotoUrl = $reporte->foto ? $reporte->foto->url_foto : null;
            return $reporte;
        });
        return response()->json($reportes);
    }

    public function show(Reporte $reporte)
    {
        $reporte->load(['usuario', 'foto']);
        $reporte->fotoUrl = $reporte->foto ? $reporte->foto->url_foto : null;
        return response()->json($reporte);
    }

    public function store(Request $request)
    {
        try {
            // 🔥 Normalizar datos antes de validar
            $data = $request->all();
            
            // Extraer usuario_id de diferentes formatos
            if (!isset($data['usuario_id'])) {
                if (isset($data['usuarioId'])) {
                    $data['usuario_id'] = $data['usuarioId'];
                } elseif (isset($data['usuario']['usuarioId'])) {
                    $data['usuario_id'] = $data['usuario']['usuarioId'];
                } elseif (isset($data['usuario']['usuario_id'])) {
                    $data['usuario_id'] = $data['usuario']['usuario_id'];
                } elseif (isset($data['user_id'])) {
                    $data['usuario_id'] = $data['user_id'];
                }
            }

            // Merge datos normalizados
            $request->merge($data);

            // Validación
            $validated = $request->validate([
                'usuario_id' => 'required|exists:usuarios,usuario_id',
                'tipo' => 'required|string',
                'descripcion' => 'nullable|string',
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
                'fotoUrl' => 'nullable|url',
                'foto_url' => 'nullable|url',
                'estado' => 'nullable|string',
            ]);

            // Crear reporte
            $reporte = $this->reporteService->createFromRequest($request);
            
            // Cargar relaciones
            $reporte->load(['usuario', 'foto']);

            return response()->json($reporte, 201);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Reporte $reporte)
    {
        try {
            $data = $request->validate([
                'tipo' => 'sometimes|required|string',
                'descripcion' => 'nullable|string',
                'latitud' => 'nullable|numeric',
                'longitud' => 'nullable|numeric',
                'foto_id' => 'nullable|exists:fotosreportes,foto_id',
                'estado' => 'nullable|string',
            ]);

            $updated = $this->reporteService->update($reporte->reporte_id, $data);
            $updated->load(['usuario', 'foto']);
            
            return response()->json($updated, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Reporte $reporte)
    {
        try {
            $this->reporteService->deleteById($reporte->reporte_id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el reporte'
            ], 500);
        }
    }
}