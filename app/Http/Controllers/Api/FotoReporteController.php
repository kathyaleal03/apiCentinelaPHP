<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FotoReporteService;
use Illuminate\Http\Request;

class FotoReporteController extends Controller
{
    protected $service;

    public function __construct(FotoReporteService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }

    public function index()
    {
        return response()->json($this->service->findAll(), 200);
    }

    public function show($id)
    {
        $f = $this->service->findById($id);
        if (!$f) return response()->json(null, 404);
        return response()->json($f, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'url_foto' => 'required|url',
        ]);

        $foto = $this->service->save($data);
        return response()->json($foto, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'url_foto' => 'required|url',
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
