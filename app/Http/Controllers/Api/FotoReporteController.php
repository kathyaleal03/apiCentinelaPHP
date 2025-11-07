<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FotoReporte;
use Illuminate\Http\Request;

class FotoReporteController extends Controller
{
    public function index()
    {
        return FotoReporte::all();
    }

    public function show(FotoReporte $fotoreporte)
    {
        return $fotoreporte;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'url_foto' => 'required|url',
        ]);

        $foto = FotoReporte::create($data);
        return response($foto, 201);
    }

    public function update(Request $request, FotoReporte $fotoreporte)
    {
        $data = $request->validate([
            'url_foto' => 'required|url',
        ]);

        $fotoreporte->update($data);
        return $fotoreporte;
    }

    public function destroy(FotoReporte $fotoreporte)
    {
        $fotoreporte->delete();
        return response(null, 204);
    }
}
