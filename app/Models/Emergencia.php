<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencia extends Model
{
    use HasFactory;

    protected $table = 'emergencias';

    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(User::class, 'user_id');
    }
}
