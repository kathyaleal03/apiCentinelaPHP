<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ComentarioService;
use Illuminate\Http\Request;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    protected $service;

    public function __construct(ComentarioService $service)
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
        $c = $this->service->findById($id);
        if (!$c) return response()->json(null, 404);
        return response()->json($c, 200);
    }

   public function store(Request $request)
    {
       
        $data = $request->validate([
            'reporte.reporteId' => 'required|exists:reportes,reporte_id',
            'usuario.usuarioId' => 'required|exists:usuarios,usuario_id',
            'mensaje' => 'required|string',
        ]);

        
        $datosParaInsertar = [
        'reporte_id' => $data['reporte']['reporteId'],
        'usuario_id' => $data['usuario']['usuarioId'],
        'mensaje' => $data['mensaje'],
        'fecha' => now() 
        ];

        
        $comentario = $this->service->save($datosParaInsertar); 
        

        return response()->json($comentario, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'mensaje' => 'required|string',
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
