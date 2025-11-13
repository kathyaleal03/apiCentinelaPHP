<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Alerta;
use App\Models\Emergencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstadisticasController extends Controller
{
    /**
     * Estadísticas por tipo de reporte
     */
    public function tipos()
    {
        try {
            Log::info('=== Petición GET /estadisticas/tipos ===');
            
            $result = Reporte::select('tipo', DB::raw('count(*) as cantidad'))
                ->groupBy('tipo')
                ->get();
            
            Log::info('Resultados estadísticas tipos: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en estadísticas tipos: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas por tipo',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas por estado de reporte
     */
    public function estados()
    {
        try {
            Log::info('=== Petición GET /estadisticas/estados ===');
            
            $result = Reporte::select('estado', DB::raw('count(*) as cantidad'))
                ->groupBy('estado')
                ->get();
            
            Log::info('Resultados estadísticas estados: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en estadísticas estados: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas por estado',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas por región (basadas en usuarios que reportan)
     */
    public function regiones()
    {
        try {
            Log::info('=== Petición GET /estadisticas/regiones ===');
            
            // Agrupar por región del usuario que hizo el reporte
            $result = Reporte::join('usuarios', 'reportes.usuario_id', '=', 'usuarios.usuario_id')
                ->select('usuarios.region', DB::raw('count(*) as cantidad'))
                ->whereNotNull('usuarios.region')
                ->groupBy('usuarios.region')
                ->get();
            
            Log::info('Resultados estadísticas regiones: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en estadísticas regiones: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas por región',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Heatmap de reportes (agrupando por lat/lng redondeados)
     */
    public function heatmap(Request $request)
    {
        try {
            Log::info('=== Petición GET /estadisticas/heatmap ===');
            
            $precision = $request->input('precision', 3);
            
            $result = Reporte::select(
                DB::raw("ROUND(latitud, $precision) as latitud"),
                DB::raw("ROUND(longitud, $precision) as longitud"),
                DB::raw('count(*) as cantidad')
            )
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->groupBy(DB::raw("ROUND(latitud, $precision)"), DB::raw("ROUND(longitud, $precision)"))
            ->get();
            
            Log::info('Resultados heatmap (precision=' . $precision . '): ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en heatmap: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener datos de heatmap',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas por nivel de alerta
     */
    public function nivelesAlerta()
    {
        try {
            Log::info('=== Petición GET /estadisticas/niveles-alerta ===');
            
            $result = Alerta::select('nivel', DB::raw('count(*) as cantidad'))
                ->groupBy('nivel')
                ->get();
            
            Log::info('Resultados niveles de alerta: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en niveles de alerta: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas de niveles de alerta',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas de emergencias atendidas/no atendidas
     */
    public function emergenciasAtendidas()
    {
        try {
            Log::info('=== Petición GET /estadisticas/emergencias/atendidos ===');
            
            $result = Emergencia::select(
                DB::raw('CASE WHEN atendido = 1 THEN "Atendidas" ELSE "No Atendidas" END as estado'),
                DB::raw('count(*) as cantidad')
            )
            ->groupBy('atendido')
            ->get();
            
            Log::info('Resultados emergencias atendidas: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en emergencias atendidas: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas de emergencias',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas de alertas por región
     */
    public function alertasPorRegion()
    {
        try {
            Log::info('=== Petición GET /estadisticas/alertas/regiones ===');
            
            $result = Alerta::join('regiones', 'alertas.region_id', '=', 'regiones.region_id')
                ->select('regiones.nombre as region', DB::raw('count(*) as cantidad'))
                ->groupBy('regiones.nombre', 'regiones.region_id')
                ->get();
            
            Log::info('Resultados alertas por región: ' . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error en alertas por región: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener estadísticas de alertas por región',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard general con todas las estadísticas
     */
    public function dashboard()
    {
        try {
            Log::info('=== Petición GET /estadisticas/dashboard ===');
            
            $dashboard = [
                'totalReportes' => Reporte::count(),
                'totalAlertas' => Alerta::count(),
                'totalEmergencias' => Emergencia::count(),
                'totalUsuarios' => \App\Models\Usuario::count(),
                'reportesPorTipo' => Reporte::select('tipo', DB::raw('count(*) as cantidad'))
                    ->groupBy('tipo')
                    ->get(),
                'reportesPorEstado' => Reporte::select('estado', DB::raw('count(*) as cantidad'))
                    ->groupBy('estado')
                    ->get(),
                'alertasPorNivel' => Alerta::select('nivel', DB::raw('count(*) as cantidad'))
                    ->groupBy('nivel')
                    ->get(),
                'emergenciasAtendidas' => Emergencia::where('atendido', true)->count(),
                'emergenciasPendientes' => Emergencia::where('atendido', false)->count(),
            ];
            
            Log::info('Dashboard generado exitosamente');
            
            return response()->json($dashboard);
        } catch (\Exception $e) {
            Log::error('Error en dashboard: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al generar dashboard',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
