<?php

namespace App\Models\Custodiado;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\PessoaAccessors;

class Pessoa extends Model
{
    use HasFactory, PessoaAccessors;

    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoas';

    /** Ajuste se seus IDs reais forem outros */
    public const DOC_TIPO_CPF = 2;
    public const DOC_TIPO_RG  = 1;

    protected $fillable   = [
        // dados pessoais
        'nome',
        'homonimo',
        'nome_social',
        'usar_social',
        'alcunha',
        'nascimento',
        'pais_id',
        'estado_id',
        'natural_id',
        'sexo_id',
        'mae',
        'pai',
        // dados gerais
        'cutis_id',
        'cor_olhos_id',
        'altura',
        'cor_cabelo_id',
        'religiao_id',
        'escolaridade_id',
        'estado_civil_id',
        'pne_id',
        'profissao_id',
        'tribo_indigena_id',
        'orientacao_sexual_id',
        'identidade_generos_id',
        // identidade fotográfica
        'img',
        'img_type',
        'img_size',
        'principal_pessoa_foto_id',
        // legado
        'idpessoa',
    ];

    protected $casts = [
        'nascimento' => 'date:Y-m-d', // GET bruto; exibição use nascimento_br
    ];

    /* =========================
     |  RELACIONAMENTOS
     |=========================*/

    public function contatos(): HasMany
    {
        return $this->hasMany(PessoaContato::class, 'pessoa_id', 'id');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id', 'id');
    }

    /** Versão “enxuta” para telas que não precisam de tudo */
    public function documentosSlim(): HasMany
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id', 'id')
            ->select([
                'id',
                'pessoa_id',
                'documento_tipo_id',
                'expedicao_estado_id',
                'expedicao_pais_id',
                'documento_numero',
                'data_expedicao',
                'orgao_expedicao',
                'descricao',
            ]);
    }

    public function custodiado(): HasOne
    {
        return $this->hasOne(Custodiado::class, 'pessoa_id', 'id');
    }

    public function vinculado(): HasOne
    {
        return $this->hasOne(Vinculado::class, 'vinculado_pessoa_id', 'id');
    }

    public function fotos()
    {
        return $this->hasMany(\App\Models\Custodiado\PessoaFoto::class, 'pessoa_id', 'id')
            ->select(['id', 'pessoa_id', 'img', 'img_type', 'foto_tipo_id', 'foto_posicao_id']); // só o necessário
    }

    public function fotoPrincipal()
    {
        return $this->belongsTo(PessoaFoto::class, 'principal_pessoa_foto_id', 'id');
    }

    /** Pega o endereço “mais recente” por id */
    public function endereco(): HasOne
    {
        return $this->hasOne(PessoaEndereco::class, 'pessoa_id', 'id')->latestOfMany('id');
    }

    // public function enderecoAtual(){
    //     return $this->enderecoAtual();
    // }
}
