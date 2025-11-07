<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlertaService;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    protected $service;

    public function __construct(AlertaService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }

    public function index()
    {
        return response()->json($this->service->findAll(), 200);
    }

    
    public function getAllAlert()
    {
        return response()->json($this->service->findAll(), 200);
    }

    public function show($id)
    {
        $a = $this->service->findById($id);
        if (!$a) return response()->json(null, 404);
        return response()->json($a, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'nullable|exists:regiones,id',
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'nivel' => 'nullable|string',
            'usuario_id' => 'required|exists:Usuarios,usuario_id',
        ]);

        $alerta = $this->service->save($data);
        return response()->json($alerta, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'region_id' => 'nullable|exists:regiones,id',
            'titulo' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'nivel' => 'nullable|string',
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
