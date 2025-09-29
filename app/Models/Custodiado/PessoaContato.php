<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaContato extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoa_contatos';
    protected $fillable   = [
        'pessoa_id',
        'contato_tipo_id',
        'contato',
        'observacao',
        'nome',
        'vinculado_tipo_id',
    ];

    public function pessoa(){
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function contatoTipo(){
        return $this->hasOne(ContatoTipo::class, 'id', 'contato_tipo_id');
    }

    public function vinculadoTipo(){
        return $this->hasOne(VinculadoTipo::class, 'id', 'vinculado_tipo_id');
    }
}
