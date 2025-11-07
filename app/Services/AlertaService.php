<?php

namespace App\Services;

use App\Models\Alerta;

class AlertaService
{
    public function findAll()
    {
        return Alerta::with(['region', 'usuario'])->get();
    }

    public function findById($id)
    {
        return Alerta::find($id);
    }

    public function save(array $data)
    {
        return Alerta::create($data);
    }

    public function deleteById($id)
    {
        return Alerta::destroy($id);
    }

    public function update($id, array $data)
    {
        $a = Alerta::find($id);
        if (!$a) return null;
        $a->fill($data);
        $a->save();
        return $a;
    }
}
