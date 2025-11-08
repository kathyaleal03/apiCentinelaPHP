<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;
    protected $table = 'regiones';
    protected $primaryKey = 'region_id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'latitud',
        'longitud',
    ];

 
    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class, 'region_id', 'region_id');
    }
}