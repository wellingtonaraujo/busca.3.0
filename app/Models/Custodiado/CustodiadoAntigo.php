<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustodiadoAntigo extends Model
{
    use HasFactory;

    protected $connection = 'siapen';
    protected $table = 'interno'; // tabela correta (pelo seu dump)
    protected $primaryKey = 'id';
    public $timestamps = true; // no dump aparecia timestamps

    protected $fillable = [
        'idinterno',
        'idpessoa',
        'alcunha',
        'idprisao_regime',
        'idsituacao_atual',
        'idalojamento',
    ];

    public function pessoa()
    {
        // interno.idpessoa → tbpessoa.id
        return $this->belongsTo(PessoaAntiga::class, 'idpessoa', 'id');
    }

    public function regime(){
        return $this->hasOne(Regime::class, 'idprisao_regime', 'idprisao_regime');
    }

    public function situacaoAtual(){
        return $this->hasOne(CustodiadoSituacaoAtual::class, 'geral_status_idstatus', 'idsituacao_atual');
    }

    public function vinculadoAntigo(){
        return $this->hasOne(VinculadoAntigo::class, 'idinterno', 'idinterno');
    }
}
