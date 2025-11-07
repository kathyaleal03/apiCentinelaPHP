<?php

namespace App\Services;

use App\Models\Emergencia;

class EmergenciaService
{
    public function findAll()
    {
        return Emergencia::with('usuario')->get();
    }

    public function findById($id)
    {
        return Emergencia::find($id);
    }

    public function save(array $data)
    {
        return Emergencia::create($data);
    }

    public function deleteById($id)
    {
        return Emergencia::destroy($id);
    }

    public function update($id, array $data)
    {
        $e = Emergencia::find($id);
        if (!$e) return null;
        $e->fill($data);
        $e->save();
        return $e;
    }
}
