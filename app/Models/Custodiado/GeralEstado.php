<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralEstado extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_estado';
    protected $fillable = [
        'idestado',
        'idpais',
        'estado',
        'sigla',
    ];
}
