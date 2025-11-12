<?php

namespace App\Models;

use App\Enums\NivelAlerta; // Importamos el Enum
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

   
    protected $table = 'alertas';

    protected $primaryKey = 'alerta_id';

    public $timestamps = false;

    protected $fillable = [
        'region_id',
        'titulo',
        'descripcion',
        'nivel',
        'id_usuario', 
    ];


    protected $casts = [
        'nivel' => 'string', 
    ];

   
    public function region(): BelongsTo
    {
        
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

   
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'usuario_id');
    }
}