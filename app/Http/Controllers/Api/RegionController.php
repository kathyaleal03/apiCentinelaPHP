<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RegionService;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    protected $service;

    public function __construct(RegionService $service)
    {
        $this->service = $service;
        // $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }

    public function index()
    {
        return response()->json($this->service->findAll(), 200);
    }

  

    public function show($id)
    {
        $r = $this->service->findById($id);
        if (!$r) return response()->json(null, 404);
        return response()->json($r, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $region = $this->service->save($data);
        return response($region, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
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
