<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'descripcion',
        'latitud',
        'longitud',
        'foto_id',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    public function foto()
    {
        // explicitly set ownerKey to match FotoReporte primary key
        return $this->belongsTo(FotoReporte::class, 'foto_id', 'foto_id');
    }
}
