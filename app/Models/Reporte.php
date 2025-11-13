<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\FotoReporte;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';
    protected $primaryKey = 'reporte_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'tipo',
        'descripcion',
        'latitud',
        'longitud',
        'foto_id',
        'estado',
        'fecha_hora',
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }

    public function foto()
    {
        
        return $this->belongsTo(FotoReporte::class, 'foto_id', 'foto_id');
    }
}