<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        return Region::all();
    }

    public function show(Region $region)
    {
        return $region;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $region = Region::create($data);
        return response($region, 201);
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        $region->update($data);
        return $region;
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return response(null, 204);
    }
}
