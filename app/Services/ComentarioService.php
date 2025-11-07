<?php

namespace App\Services;

use App\Models\Comentario;

class ComentarioService
{
    public function findAll()
    {
        return Comentario::with(['reporte', 'usuario'])->get();
    }

    public function findById($id)
    {
        return Comentario::find($id);
    }

    public function save(array $data)
    {
        return Comentario::create($data);
    }

    public function deleteById($id)
    {
        return Comentario::destroy($id);
    }

    public function update($id, array $data)
    {
        $c = Comentario::find($id);
        if (!$c) return null;
        $c->fill($data);
        $c->save();
        return $c;
    }
}
