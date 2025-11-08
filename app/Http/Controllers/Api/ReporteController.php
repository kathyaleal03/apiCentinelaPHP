<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\FotoReporte; // Asegúrate de tener este modelo
use Illuminate\Http\Request;
use App\Services\ReporteService;

class ReporteController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteService $reporteService)
    {
        $this->reporteService = $reporteService;
        // $this->middleware('auth:sanctum')->only(['store','update','destroy']);
    }

    /**
     * Muestra todos los reportes con sus relaciones.
     */
    public function index()
    {
        return Reporte::with(['usuario', 'foto'])->get();
    }

    /**
     * Muestra un reporte específico (usa Route Model Binding).
     */
    public function show(Reporte $reporte)
    {
        return $reporte->load(['usuario', 'foto']);
    }

    /**
     * Guarda un nuevo reporte, creando primero la foto si se provee.
     */
    public function store(Request $request)
    {
        // 1. Validar todos los datos de entrada
        // MODIFICADO: para aceptar 'usuario.usuarioId' y 'estado'
        $validatedData = $request->validate([
            // Validamos la nueva estructura del payload
            'usuario' => 'required|array',
            'usuario.usuarioId' => 'required|exists:Usuarios,usuario_id', // Validamos el ID anidado
            
            'tipo' => 'sometimes|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'fotoUrl' => 'nullable|url',
            'foto_url' => 'nullable|string', // Aceptamos ambos por si acaso
            'estado' => 'sometimes|string', // Aceptamos el estado del frontend
        ]);

        $fotoId = null;
        // Unificar la URL de la foto (priorizando fotoUrl)
        $fotoUrl = $request->input('fotoUrl', $request->input('foto_url'));

        // 2. Lógica para guardar la foto PRIMERO
        if (!empty($fotoUrl)) {
            // Creamos el registro en la tabla de fotos
            $nuevaFoto = FotoReporte::create([
                'url' => $fotoUrl,
                // 'otra_columna' => 'otro_valor' // ... si tienes más columnas
            ]);
            
            // 3. Extraemos el ID de la foto recién creada
            // (ASUNCIÓN: tu PK se llama foto_id y está en $fillable de FotoReporte)
            $fotoId = $nuevaFoto->foto_id; 
        }

        // 4. Crear el Reporte usando el ID de la foto
        
        // Preparamos los datos para el reporte
        // MODIFICADO: extraemos el usuarioId de la nueva estructura
        $reporteData = [
            'usuario_id' => $request->input('usuario.usuarioId'), // <-- Extraído del objeto anidado
            'tipo' => $request->input('tipo') ?? null,
            'descripcion' => $request->input('descripcion') ?? null,
            'latitud' => $request->input('latitud') ?? null,
            'longitud' => $request->input('longitud') ?? null,
            'foto_id' => $fotoId, // <--- ¡AQUÍ ESTÁ! Usamos el ID extraído
            'estado' => $request->input('estado', 'Pendiente'), // <-- Usamos el estado del front o 'Pendiente'
        ];

        // 5. Creamos el reporte
        // (ASUNCIÓN: todos los campos de $reporteData están en $fillable de Reporte)
        $reporte = Reporte::create($reporteData);

        // Cargamos las relaciones para devolver el objeto completo
        $reporte->load(['usuario', 'foto']);

        return response($reporte, 201);
    }

    /**
     * Actualiza un reporte existente (usa Route Model Binding).
     */
    public function update(Request $request, Reporte $reporte)
    {
        $data = $request->validate([
            'tipo' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            // fotosreportes uses foto_id as primary key in the DB
            'foto_id' => 'nullable|exists:fotosreportes,foto_id',
            'estado' => 'nullable|string',
        ]);

        $reporte->update($data);
        return $reporte;
    }

    public function destroy(Reporte $reporte)
    {
        $reporte->delete();
        return response(null, 204);
    }
}