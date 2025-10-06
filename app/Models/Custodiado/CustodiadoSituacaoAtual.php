<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustodiadoSituacaoAtual extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table = 'custodiado_situacao_atuals';
    protected $fillable = [
        'geral_status_idstatus',
        'descricao',
        'situacao_tipo',
    ];
}
