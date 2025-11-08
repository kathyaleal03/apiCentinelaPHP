<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Emergencia extends Model
{
    use HasFactory;

    protected $table = 'emergencias';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'latitud',
        'longitud',
        'atendido',
    ];

    protected $casts = [
        'atendido' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }
}
