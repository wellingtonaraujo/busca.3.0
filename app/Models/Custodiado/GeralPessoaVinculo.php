<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralPessoaVinculo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_pessoa_vinculo';
    protected $fillable = [
        'idvinculo',
        'vinculo',
        'idtipo_vinculo',
        'sexo',
    ];
}
