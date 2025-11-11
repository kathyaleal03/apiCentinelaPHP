<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';
    public $timestamps = false;
    protected $fillable = [
        'reporte_id',
        'usuario_id',
        'mensaje',
        'fecha',
    ];

    public function reporte()
    {
        return $this->belongsTo(Reporte::class, 'reporte_id', 'reporte_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }
}
