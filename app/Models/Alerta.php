<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Region; // Asegúrate de importar Region

class Alerta extends Model
{
    use HasFactory;

    /**
     * CORRECCIÓN 1:
     * El nombre de la tabla en tu base de datos (MySQL) es 'Alertas' (con mayúscula).
     */
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

 
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function usuario()
    {
       
        return $this->belongsTo(Usuario::class, 'id_usuario', 'usuario_id');
    }
}