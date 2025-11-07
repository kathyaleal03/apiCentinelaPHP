<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoReporte extends Model
{
    use HasFactory;

    protected $table = 'fotosreportes';

    // The existing database uses `foto_id` as primary key in some setups.
    // Set the primary key explicitly so eager loading uses the correct column.
    protected $primaryKey = 'foto_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'url_foto',
    ];
}
