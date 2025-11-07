<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';

    protected $fillable = [
        'region_id',
        'titulo',
        'descripcion',
        'nivel',
        'usuario_id',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }
}
