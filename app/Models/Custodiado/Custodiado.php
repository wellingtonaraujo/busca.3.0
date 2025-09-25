<?php

namespace App\Models\Custodiado;

use App\Classes\Datas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custodiado extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table      = 'custodiados';
    protected $fillable = [
        'pessoa_id',
        'regime_id',
        'custodiado_situacao_atual_id',
        'cnc',
        'rji',
        'numero_arquivo',
        'biometria',
        'dna',
        'idinterno',
    ];

    public function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function regime()
    {
        return $this->hasOne(Regime::class, 'id', 'regime_id');
    }

    public function situacaoAtual()
    {
        return $this->hasOne(CustodiadoSituacaoAtual::class, 'id', 'custodiado_situacao_atual_id');
    }

    public function regimeSituacaoAtual()
    {
        try {
            $regime = $this->regime;
            return $regime->descricao;
        } catch (\Error $e) {
            $situacaoAtual = $this->situacaoAtual;
            return $situacaoAtual->descricao;
        } catch (\Throwable $th) {
            return '';
        }
    }

    public function custodiadoAntigo()
    {
        // return $this->hasOne(Interno::class, 'idinterno', 'idinterno');
    }
}
