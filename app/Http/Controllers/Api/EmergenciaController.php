<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmergenciaService;
use Illuminate\Http\Request;

class EmergenciaController extends Controller
{
    protected $service;

    public function __construct(EmergenciaService $service)
    {
        $this->service = $service;
        
    }

    public function index()
    {
        return response()->json($this->service->findAll(), 200);
    }

    public function show($id)
    {
        $e = $this->service->findById($id);
        if (!$e) return response()->json(null, 404);
        return response()->json($e, 200);
    }

   
    public function store(Request $request)
    {
        
        $data = $request->validate([
            'usuario.usuarioId' => 'required|exists:Usuarios,usuario_id', 
            'mensaje' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'atendido' => 'nullable|boolean', 
        ]);


        $flatData = [
            'usuario_id' => $data['usuario']['usuarioId'], 
            'mensaje' => $data['mensaje'],
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'atendido' => $data['atendido'] ?? false,
        ];

        
        $emergencia = $this->service->save($flatData);

        return response()->json($emergencia, 201);
    }

    public function update(Request $request, $id)
    {
        
        $data = $request->validate([
            'mensaje' => 'sometimes|required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'atendido' => 'nullable|boolean',
        ]);

        $updated = $this->service->update($id, $data);
        if (!$updated) return response()->json(null, 404);
        return response()->json($updated, 200);
    }

    public function destroy($id)
    {
        $exists = $this->service->findById($id);
        if (!$exists) return response()->json(null, 404);
        $this->service->deleteById($id);
        return response()->json(null, 204);
    }
}