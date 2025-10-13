<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PessoaAntiga extends Model
{
    use HasFactory;

    /** Conexão/tabela do banco legado */
    protected $connection = 'siapen';
    protected $table = 'tbpessoa';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /** Campos básicos (ajuste conforme seu schema) */
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

    /** Casts úteis */
    protected $casts = [
        'nascimento' => 'date:Y-m-d',
        'altura'     => 'float',
    ];

    /* -----------------------------------------------------------------
     |  Relações (SEM get()/first())
     |------------------------------------------------------------------*/

    /** PessoaAntiga -> CustodiadoAntigo (interno) */
    public function custodiadoAntigo(): HasOne
    {
        // interno.idpessoa → tbpessoa.id
        return $this->hasOne(CustodiadoAntigo::class, 'idpessoa', 'id');
    }

    /** PessoaAntiga sendo VISITANTE em vínculos */
    public function vinculadosComoVisitante(): HasMany
    {
        return $this->hasMany(
            VinculadoAntigo::class,
            'idvisitante', // FK em vinculado_antigo
            'id'           // PK local (tbpessoa.id)
        );
    }

    /** PessoaAntiga sendo INTERNO em vínculos */
    public function vinculadosComoInterno(): HasManyThrough
    {
        return $this->hasManyThrough(
            VinculadoAntigo::class,  // tabela final
            CustodiadoAntigo::class, // tabela intermediária
            'idpessoa',              // FK em custodiado_antigo → tbpessoa.id
            'idinterno',             // FK em vinculado_antigo → custodiado_antigo.idinterno
            'id',                    // PK local (tbpessoa.id)
            'idinterno'              // PK/coluna alvo em custodiado_antigo usada no vínculo
        );
    }

    /** Documentos da pessoa no banco antigo */
    public function documentosAntigos(): HasMany
    {
        return $this->hasMany(PessoaDocumentoAntigo::class, 'idpessoa', 'id');
    }

    /** Contatos da pessoa no banco antigo */
    public function contatosAntigos(): HasMany
    {
        return $this->hasMany(PessoaContatoAntigo::class, 'idpessoa', 'id');
    }

    /** Relação auxiliar usada no whereHas do search */
    public function internoVisitante(): HasOne
    {
        return $this->hasOne(InternoVisitante::class, 'idvisitante', 'id');
    }

    /* -----------------------------------------------------------------
     |  Accessors / Atributos computados
     |------------------------------------------------------------------*/

    /**
     * Accessor que mescla os vínculos em que a pessoa é visitante + interno.
     * Não é uma relação Eloquent; serve para leitura unificada sem quebrar eager loading.
     *
     * Uso: $model->vinculado_antigo  (ou inclua em $appends para sair no JSON)
     */
    public function getVinculadoAntigoAttribute()
    {
        $visitantes = $this->relationLoaded('vinculadosComoVisitante')
            ? $this->getRelation('vinculadosComoVisitante')
            : $this->vinculadosComoVisitante()->get();

        $internos = $this->relationLoaded('vinculadosComoInterno')
            ? $this->getRelation('vinculadosComoInterno')
            : $this->vinculadosComoInterno()->get();

        // concat preserva chaves; values() reindexa
        return $visitantes->concat($internos)->values();
    }

    // Se quiser que o atributo apareça automaticamente no JSON:
    // protected $appends = ['vinculado_antigo'];
}
