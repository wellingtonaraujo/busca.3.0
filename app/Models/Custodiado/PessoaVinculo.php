<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaVinculo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'pessoa_vinculo';
    protected $fillable = [
        'idpessoa',
        'idpessoa_vinculo', //referesse a pessoa do interno
        'contato',
        'idvinculo', //referesse ao idvinculo da tabela geral_pessoa_vinculo, tipo de vinculo
        'obs',
    ];

    public function parentesco(){
        return $this->hasOne(GeralPessoaVinculo::class, 'idvinculo', 'idvinculo');
    }
}
