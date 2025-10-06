<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralPais extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_pais';
    protected $fillable = [
        'idpais',
        'pais',
        'sigla',
    ];
}
