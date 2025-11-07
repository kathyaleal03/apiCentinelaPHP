<?php

namespace App\Services;

use App\Models\Region;

class RegionService
{
    public function findAll()
    {
        return Region::all();
    }

    public function findById($id)
    {
        return Region::find($id);
    }

    public function save(array $data)
    {
        return Region::create($data);
    }

    public function deleteById($id)
    {
        return Region::destroy($id);
    }

    public function update($id, array $data)
    {
        $r = Region::find($id);
        if (!$r) return null;
        $r->fill($data);
        $r->save();
        return $r;
    }
}
