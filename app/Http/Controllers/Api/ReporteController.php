<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\ReporteService;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function index()
    {
       
        $today = Carbon::today()->toDateString();
        
        $reportes = Reporte::with(['usuario', 'foto'])
            ->whereDate('fecha_hora', $today)
            ->get();

        if ($reportes->isEmpty()) {
            return response()->json($reportes);
        }

       
        $ids = $reportes->pluck('reporte_id')->all();
        $counts = Comentario::select('reporte_id', DB::raw('count(*) as total'))
            ->whereIn('reporte_id', $ids)
            ->groupBy('reporte_id')
            ->get()
            ->pluck('total', 'reporte_id')
            ->toArray();

        $minVisibleTime = Carbon::now()->subMinutes(3);

        
        $visible = $reportes->filter(function ($r) use ($counts, $minVisibleTime) {
            $hasComments = isset($counts[$r->reporte_id]) && $counts[$r->reporte_id] > 0;
            if ($hasComments) return true;

            if (empty($r->fecha_hora)) return false;
            try {
                $created = Carbon::parse($r->fecha_hora);
                return $created->greaterThanOrEqualTo($minVisibleTime);
            } catch (\Exception $e) {
                return false;
            }
        })->values();

        
        $visible->transform(function ($reporte) use ($counts) {
            $reporte->fotoUrl = $reporte->foto ? $reporte->foto->url_foto : null;
            $reporte->comentariosCount = $counts[$reporte->reporte_id] ?? 0;
            return $reporte;
        });

        return response()->json($visible);
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
          
            $data = $request->all();
            
            
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

          
            $request->merge($data);

          
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

    
            $reporte = $this->reporteService->createFromRequest($request);
            
       
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