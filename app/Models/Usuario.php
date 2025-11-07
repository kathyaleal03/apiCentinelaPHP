<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'Usuarios';
    protected $primaryKey = 'usuario_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'telefono',
        'departamento',
        'ciudad',
        'region',
        'rol' 
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Laravel expects getAuthPassword to return the password field name
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // convenience: map moment-like fields if needed
}
