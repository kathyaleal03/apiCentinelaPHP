<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoReporte extends Model
{
    use HasFactory;

    protected $table = 'fotosreportes';
    public $timestamps = false;


    protected $primaryKey = 'foto_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'url_foto',
    ];
}
