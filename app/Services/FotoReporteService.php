<?php

namespace App\Services;

use App\Models\FotoReporte;

class FotoReporteService
{
    public function findAll()
    {
        return FotoReporte::all();
    }

    public function findById($id)
    {
        return FotoReporte::find($id);
    }

    public function save(array $data)
    {
        \Log::info('FotoReporteService - Intentando guardar foto con data: ' . json_encode($data));
        
        $foto = FotoReporte::create($data);
        
        \Log::info('FotoReporteService - Foto guardada exitosamente', [
            'foto_id' => $foto->foto_id,
            'url_foto' => $foto->url_foto
        ]);
        
        return $foto;
    }

    public function deleteById($id)
    {
        return FotoReporte::destroy($id);
    }

    public function update($id, array $data)
    {
        $f = FotoReporte::find($id);
        if (!$f) return null;
        $f->fill($data);
        $f->save();
        return $f;
    }
}
