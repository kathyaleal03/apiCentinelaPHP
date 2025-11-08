<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlertaService; 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator; 

class AlertaController extends Controller
{
    /**
     * @var AlertaService
     */
    protected $alertaService;

    
    public function __construct(AlertaService $alertaService)
    {
        $this->alertaService = $alertaService;
    }

  
    public function getAllAlert(): JsonResponse
    {
        $alertas = $this->alertaService->findAll();
        return response()->json($alertas, 200);
    }

 
    public function getAlertaById(int $id): JsonResponse
    {
        $alerta = $this->alertaService->findById($id);

        if ($alerta) {
            return response()->json($alerta, 200); 
        } else {
            return response()->json(['message' => 'Alerta no encontrada'], 404); 
        }
    }

    
    public function createAlerta(Request $request): JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'nivel' => 'required|string',
            
            
            'region' => 'present|nullable|array', 
            'region.regionId' => 'nullable|integer|exists:regiones,region_id', 
            
            
            'usuario' => 'required|array',
            'usuario.usuarioId' => 'required|integer|exists:usuarios,usuario_id' 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $alertaDataParaServicio = [
            'titulo' => $validatedData['titulo'],
            'descripcion' => $validatedData['descripcion'],
            'nivel' => $validatedData['nivel'],
            
            
            'id_usuario' => $validatedData['usuario']['usuarioId'],
            
            'region_id' => $validatedData['region']['regionId'] ?? null
        ];

        
        $nuevaAlerta = $this->alertaService->save($alertaDataParaServicio);
        return response()->json($nuevaAlerta, 201); 
    }

   
    public function updateAlerta(Request $request, int $id): JsonResponse
    {
        
        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'nivel' => 'sometimes|required|string',

            'region' => 'sometimes|present|nullable|array',
            'region.regionId' => 'nullable|integer|exists:regions,region_id',

            'usuario' => 'sometimes|required|array',
            'usuario.usuarioId' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        
        $alertaDataParaServicio = [];

        if (isset($validatedData['titulo'])) {
            $alertaDataParaServicio['titulo'] = $validatedData['titulo'];
        }
        if (isset($validatedData['descripcion'])) {
            $alertaDataParaServicio['descripcion'] = $validatedData['descripcion'];
        }
        if (isset($validatedData['nivel'])) {
            $alertaDataParaServicio['nivel'] = $validatedData['nivel'];
        }
        if (isset($validatedData['usuario'])) {
            $alertaDataParaServicio['id_usuario'] = $validatedData['usuario']['usuarioId'];
        }
        
        if (array_key_exists('region', $validatedData)) {
            $alertaDataParaServicio['region_id'] = $validatedData['region']['regionId'] ?? null;
        }

        
        $alertaActualizada = $this->alertaService->update($id, $alertaDataParaServicio);

        if ($alertaActualizada) {
            return response()->json($alertaActualizada, 200); 
        } else {
            return response()->json(['message' => 'Alerta no encontrada'], 404); 
        }
    }

    public function deleteAlerta(int $id): JsonResponse
    {
       
        $alerta = $this->alertaService->findById($id);

        if (!$alerta) {
            return response()->json(['message' => 'Alerta no encontrada'], 404); 
        }

        $this->alertaService->deleteById($id);
        return response()->json(null, 204); 
    }
}