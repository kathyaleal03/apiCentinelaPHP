<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Region;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    // Estadísticas por tipo de reporte
    public function tipos()
    {
        $result = Reporte::select('tipo', DB::raw('count(*) as cantidad'))
            ->groupBy('tipo')
            ->get();
        return response()->json($result);
    }

    // Estadísticas por estado de reporte
    public function estados()
    {
        $result = Reporte::select('estado', DB::raw('count(*) as cantidad'))
            ->groupBy('estado')
            ->get();
        return response()->json($result);
    }

    // Estadísticas por región
    public function regiones()
    {
        $result = Reporte::select('region_id', DB::raw('count(*) as cantidad'))
            ->groupBy('region_id')
            ->get();
        return response()->json($result);
    }

    // Heatmap de reportes (ejemplo: agrupando por lat/lng redondeados)
    public function heatmap(Request $request)
    {
        $precision = $request->input('precision', 3);
        $result = Reporte::select(
            DB::raw("ROUND(latitud, $precision) as latitud"),
            DB::raw("ROUND(longitud, $precision) as longitud"),
            DB::raw('count(*) as cantidad')
        )
        ->groupBy('latitud', 'longitud')
        ->get();
        return response()->json($result);
    }

        // Estadísticas por nivel de alerta
        public function nivelesAlerta()
        {
            $result = \App\Models\Alerta::select('nivel', DB::raw('count(*) as cantidad'))
                ->groupBy('nivel')
                ->get();
            return response()->json($result);
        }

        // Estadísticas de emergencias atendidas/no atendidas
        public function emergenciasAtendidas()
        {
            $result = \App\Models\Emergencia::select('atendido', DB::raw('count(*) as cantidad'))
                ->groupBy('atendido')
                ->get();
            return response()->json($result);
        }
}
