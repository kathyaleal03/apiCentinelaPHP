<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Services\AlertaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Importar el validador
use Illuminate\Validation\Rule;

class AlertaController extends Controller
{

    protected $alertaService;

    // Inyectar el servicio
    public function __construct(AlertaService $service)
    {
        $this->alertaService = $service;
    }

    public function getAllAlert()
    {
        // El servicio ya se encarga de 'findAll'
        return response()->json($this->alertaService->findAll(), 200);
    }

    public function getAlertaById($id)
    {
        $alerta = $this->alertaService->findById($id);
        if (!$alerta) {
            return response()->json(null, 404);
        }
        return response()->json($alerta, 200);
    }


    public function createAlerta(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'nivel' => 'required|string',
            
           
            'region' => 'present|nullable|array', 
            'region.regionId' => 'nullable|integer|exists:regions,region_id', 
            
           
            'usuario' => 'required|array',
            'usuario.usuarioId' => 'required|integer|exists:users,id' 
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

       
        $nuevo = $this->alertaService->save($alertaDataParaServicio);

        return response()->json($nuevo, 201);
    }


    public function updateAlerta(Request $request, $id)
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

       
        if (isset($validatedData['region'])) {
            $alertaDataParaServicio['region_id'] = $validatedData['region']['regionId'] ?? null;
        }

        
        $actualizado = $this->alertaService->update($id, $alertaDataParaServicio);

        if ($actualizado) {
            return response()->json($actualizado, 200);
        } else {
            return response()->json(null, 404);
        }
    }

    public function deleteAlerta($id)
    {
        if ($this->alertaService->findById($id) == null) {
            return response()->json(null, 404);
        }
        $this->alertaService->deleteById($id);
        return response()->json(null, 204); // 204 No Content
    }
}