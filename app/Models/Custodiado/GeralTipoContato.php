<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralTipoContato extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_tipo_contato';
    protected $fillable = [
        'idtipo_contato',
        'tipo_contato'
    ];
}
