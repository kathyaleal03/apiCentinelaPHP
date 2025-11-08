<?php

namespace App\Services;

use App\Models\Reporte;
use App\Models\Usuario;
use App\Models\FotoReporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporteService
{
    protected $usuarioService;
    protected $fotoService;

    public function __construct(UsuarioService $usuarioService, FotoReporteService $fotoService)
    {
        $this->usuarioService = $usuarioService;
        $this->fotoService = $fotoService;
    }

    public function findAll()
    {
        return Reporte::with(['usuario', 'foto'])->get();
    }

    public function findById($id)
    {
        return Reporte::with(['usuario', 'foto'])->find($id);
    }

    public function save(array $data)
    {
        
        if (empty($data['usuario_id'])) {
            throw new \InvalidArgumentException('El reporte debe incluir usuario_id');
        }
        $u = Usuario::find($data['usuario_id']);
        if (!$u) throw new \InvalidArgumentException('Usuario no encontrado: ' . $data['usuario_id']);

        if (!empty($data['foto']) && is_array($data['foto'])) {
            $foto = $data['foto'];
            if (empty($foto['fotoId']) && !empty($foto['url_foto'])) {
                $saved = $this->fotoService->save(['url_foto' => $foto['url_foto']]);
                
                $data['foto_id'] = $saved->getKey();
            } elseif (!empty($foto['fotoId'])) {
                $data['foto_id'] = $foto['fotoId'];
            }
        }

        
        $payload = [
            'usuario_id' => $u->usuario_id,
            'tipo' => $data['tipo'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'foto_id' => $data['foto_id'] ?? null,
            'estado' => $data['estado'] ?? 'Activo',
        ];

        return Reporte::create($payload);
    }

    public function createFromRequest(Request $request)
    {
    $data = $request->only(['usuario_id','user_id', 'tipo', 'descripcion', 'latitud', 'longitud', 'fotoUrl', 'foto_url', 'estado']);
        
        $fotoUrl = $data['fotoUrl'] ?? $data['foto_url'] ?? null;
        $payload = [
            'usuario_id' => $data['usuario_id'] ?? $data['user_id'] ?? $request->input('usuario_id'),
            'tipo' => $data['tipo'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'latitud' => $data['latitud'] ?? null,
            'longitud' => $data['longitud'] ?? null,
            'estado' => $data['estado'] ?? null,
        ];

        if ($fotoUrl) {
            $f = $this->fotoService->save(['url_foto' => $fotoUrl]);
            $payload['foto_id'] = $f->getKey();
        }

        return $this->save($payload);
    }

    public function deleteById($id)
    {
        return Reporte::destroy($id);
    }

    public function update($id, array $data)
    {
        $r = Reporte::find($id);
        if (!$r) return null;

        if (isset($data['usuario_id']) || isset($data['user_id'])) {
            $uid = $data['usuario_id'] ?? $data['user_id'];
            $u = Usuario::find($uid);
            if ($u) $r->usuario_id = $u->usuario_id;
        }

        if (isset($data['foto']) && is_array($data['foto'])) {
            $foto = $data['foto'];
            if (isset($foto['fotoId'])) $r->foto_id = $foto['fotoId'];
        }

        $r->descripcion = $data['descripcion'] ?? $r->descripcion;
        $r->latitud = $data['latitud'] ?? $r->latitud;
        $r->longitud = $data['longitud'] ?? $r->longitud;
        $r->tipo = $data['tipo'] ?? $r->tipo;
        $r->estado = $data['estado'] ?? $r->estado;
        $r->save();
        return $r;
    }
}
