<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReporteService;
use App\Services\FotoReporteService; // 1. Importamos el servicio de fotos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    protected $reporteService;
    protected $fotoService; // 2. Definimos el servicio de fotos

    public function __construct(ReporteService $reporteService, FotoReporteService $fotoService)
    {
        $this->reporteService = $reporteService;
        $this->fotoService = $fotoService; // 3. Inyectamos el servicio de fotos

        
    }

    public function index()
    {
        return response()->json($this->reporteService->findAll(), 200);
    }

    public function show($id)
    {
        $reporte = $this->reporteService->findById($id);
        if (!$reporte) return response()->json(null, 404);
        return response()->json($reporte, 200);
    }

    /**
     * Almacena un nuevo reporte desde la vista de React.
     */
    public function store(Request $request)
    {
        // 1. Validar la estructura exacta que envía React
        $data = $request->validate([
            'usuario.usuarioId' => 'required|exists:Usuarios,usuario_id',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'estado' => 'nullable|string|max:100',
            'fotoUrl' => 'nullable|url',
        ]);

        // 2. "Aplanar" los datos para el servicio
        // (Similar a lo que hicimos en EmergenciaController)
        $flatData = [
            'usuario_id' => $data['usuario']['usuarioId'],
            'tipo' => $data['tipo'],
            'descripcion' => $data['descripcion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'estado' => $data['estado'] ?? 'Activo',
        ];

        try {
            // 3. Manejar la 'fotoUrl' (lógica extraída de tu ReporteService->createFromRequest)
            if (!empty($data['fotoUrl'])) {
                // Usamos el servicio de fotos para crear la foto y obtener su ID
                $foto = $this->fotoService->save(['url_foto' => $data['fotoUrl']]);
                $flatData['foto_id'] = $foto->getKey();
            }

            // 4. Guardar el reporte usando el payload aplanado
            $reporte = $this->reporteService->save($flatData);
            
            // Recargamos la relación para devolver el objeto completo
            $reporte->load(['usuario', 'foto']);

            return response()->json($reporte, 201);

        } catch (\InvalidArgumentException $e) {
            // Captura errores como "Usuario no encontrado"
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear reporte: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al crear el reporte.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
       
        
        $data = $request->validate([
            'usuario.usuarioId' => 'sometimes|required|exists:Usuarios,usuario_id',
            'tipo' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'estado' => 'nullable|string|max:100',
            'fotoUrl' => 'nullable|url',
        ]);

        // Aplanamos la data para el método update del servicio
        $flatData = $data;
        if (isset($data['usuario']['usuarioId'])) {
            $flatData['usuario_id'] = $data['usuario']['usuarioId'];
            unset($flatData['usuario']);
        }

        // Manejamos la foto
        if (isset($data['fotoUrl'])) {
            $foto = $this->fotoService->save(['url_foto' => $data['fotoUrl']]);
            $flatData['foto_id'] = $foto->getKey();
            unset($flatData['fotoUrl']); // El servicio no espera fotoUrl
        }
        
        $reporte = $this->reporteService->update($id, $flatData);
        if (!$reporte) return response()->json(null, 404);
        
        $reporte->load(['usuario', 'foto']);
        return response()->json($reporte, 200);
    }

    public function destroy($id)
    {
        $exists = $this->reporteService->findById($id);
        if (!$exists) return response()->json(null, 404);
        $this->reporteService->deleteById($id);
        return response()->json(null, 204);
    }
}