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
        return FotoReporte::create($data);
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
