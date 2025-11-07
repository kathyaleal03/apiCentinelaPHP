<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoReporte extends Model
{
    use HasFactory;

    protected $table = 'fotosreportes';

    protected $fillable = [
        'url_foto',
    ];
}
