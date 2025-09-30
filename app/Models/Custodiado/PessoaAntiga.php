<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaAntiga extends Model
{
    use HasFactory;

    protected $connection = 'siapen';
    protected $table = 'tbpessoa';
    protected $fillable = [
        'nome',
        'alcunha',
        'nascimento',
        'estado_civil_id',
        'escolaridade_id',
        'profissao_id',
        'religiao_id',
        'altura',
    ];

    public function custodiado()
    {
        return $this->hasOne(CustodiadoAntigo::class, 'idpessoa', 'id');
    }
}
