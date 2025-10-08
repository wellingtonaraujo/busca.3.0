<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaContatoAntigo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'pessoa_contato';
    protected $fillable = [
        'idpessoa_contato',
        'idpessoa',
        'idtipo_contato',
        'contato',
        'observacao',
    ];

    public function pessoaAntiga(){
        return $this->hasOne(PessoaAntiga::class, 'id', 'idpessoa');
    }

    public function tipoContato(){
        return $this->hasOne(GeralTipoContato::class, 'idtipo_contato', 'idtipo_contato');
    }
}
