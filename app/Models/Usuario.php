<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    protected $table = 'usuarios';
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
    ];
}
