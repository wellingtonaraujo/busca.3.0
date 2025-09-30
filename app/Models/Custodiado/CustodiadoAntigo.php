<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustodiadoAntigo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table      = 'interno';
    protected $fillable = [
        'idpessoa',
        'idprisao_regime',
        'idsituacao_atual',
        'nr_arquivo',
    ];

    public function pessoa()
    {
        return $this->belongsTo(PessoaAntiga::class, 'idpessoa', 'id');
    }

    public function regime()
    {
        return $this->hasOne(Regime::class, 'idprisao_regime', 'idprisao_regime');
    }

    public function situacaoAtual()
    {
        return $this->hasOne(CustodiadoSituacaoAtual::class, 'geral_status_idstatus', 'idsituacao_atual');
    }
}
