<?php

namespace App\Models\Custodiado;

use App\Traits\HasFotoSsh;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PessoaAntiga extends Model
{
    use HasFactory, HasFotoSsh;

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
        // se a tabela tiver foto em base64:
        // 'img', 'type',
    ];

    /** Casts úteis */
    protected $casts = [
        'nascimento' => 'date:Y-m-d',
        'altura'     => 'float',
    ];

    /** (Opcional) expõe 'foto' no JSON com o accessor do Trait */
    protected $appends = [
        'foto', // habilite se quiser que saia automaticamente nas APIs
        // 'vinculado_antigo', // idem
    ];

    /* ---------------- Relações ---------------- */

    public function custodiadoAntigo(): HasOne
    {
        // interno.idpessoa → tbpessoa.id
        return $this->hasOne(CustodiadoAntigo::class, 'idpessoa', 'id');
    }

    public function vinculadosComoVisitante(): HasMany
    {
        return $this->hasMany(VinculadoAntigo::class, 'idvisitante', 'id');
    }

    public function vinculadosComoInterno(): HasManyThrough
    {
        return $this->hasManyThrough(
            VinculadoAntigo::class,   // tabela final
            CustodiadoAntigo::class,  // intermediária
            'idpessoa',               // FK na intermediária → tbpessoa.id
            'idinterno',              // FK na final → custodiado_antigo.idinterno
            'id',                     // PK local
            'idinterno'               // chave usada no vínculo
        );
    }

    public function visitantes(): HasMany
    {
        return $this->hasMany(VinculadoAntigo::class, 'idvisitante', 'id');
    }

    public function documentosAntigos(): HasMany
    {
        return $this->hasMany(PessoaDocumentoAntigo::class, 'idpessoa', 'id');
    }

    public function contatosAntigos(): HasMany
    {
        return $this->hasMany(PessoaContatoAntigo::class, 'idpessoa', 'id');
    }

    public function internoVisitante(): HasOne
    {
        return $this->hasOne(InternoVisitante::class, 'idvisitante', 'id');
    }

    public function getFotoAttribute(): ?string
    {
        return $this->fotoSsh();
    }

    /* -------- Accessors computados -------- */

    public function getVinculadoAntigoAttribute()
    {
        $visitantes = $this->relationLoaded('vinculadosComoVisitante')
            ? $this->getRelation('vinculadosComoVisitante')
            : $this->vinculadosComoVisitante()->get();

        $internos = $this->relationLoaded('vinculadosComoInterno')
            ? $this->getRelation('vinculadosComoInterno')
            : $this->vinculadosComoInterno()->get();

        return $visitantes->concat($internos)->values();
    }

    /** (Opcional) se quiser declarar explicitamente para o Trait */
    // public function getLegacyPessoaIdForFoto(): ?int
    // {
    //     return $this->id; // aqui você pode customizar se necessário
    // }
}
