<?php

namespace App\Services;

use App\Models\Alerta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class AlertaService
{
    
    public function findAll(): Collection
    {
       
        return Alerta::with(['region', 'usuario'])->get();
    }

  
    public function findById(int $id): ?Model
    {
        return Alerta::find($id);
    }

    public function save(array $data): Model
    {
        return Alerta::create($data);
    }

 
    public function update(int $id, array $data): ?Model
    {
        $alerta = $this->findById($id);

        if (!$alerta) {
            return null;
        }

        $alerta->fill($data);
        $alerta->save();   
        return $alerta;
    }


    public function deleteById(int $id): int
    {
        
        return Alerta::destroy($id);
    }
}