<?php

namespace App\Models\Custodiado;

use App\Models\Adm\Estado;
use App\Models\Adm\Pais;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PessoaDocumento extends Model
{
    use HasFactory;

    protected $connection = 'siapenweb_dp';
    protected $table = 'pessoa_documentos';

    protected $fillable = [
        'pessoa_id',
        'documento_tipo_id',
        'expedicao_estado_id',
        'expedicao_pais_id',
        'documento_numero',
        'data_expedicao',
        'orgao_expedicao',
        'descricao',
    ];

    protected $casts = [
        'data_expedicao' => 'date:Y-m-d',
    ];

    /* =========================
     |  RELACIONAMENTOS
     |=========================*/

    // Cada documento PERTENCE a uma pessoa (FK em pessoa_documentos)
    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id', 'id');
    }

    // Tipo do documento (RG, CPF, etc.)
    public function documentoTipo(): BelongsTo
    {
        return $this->belongsTo(DocumentoTipo::class, 'documento_tipo_id', 'id');
    }

    // UF de expedição
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'expedicao_estado_id', 'id');
    }

    // País de expedição
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'expedicao_pais_id', 'id');
    }

    /* =========================
     |  ACCESSORS LEVES (sem N+1)
     |=========================*/

    /**
     * Abreviatura do tipo (ex.: "CPF", "RG").
     * Usa a relação já carregada; só consulta se necessário.
     */
    public function getDocumentoTipoAbrevAttribute(): ?string
    {
        if ($this->relationLoaded('documentoTipo')) {
            return optional($this->documentoTipo)->abreviatura;
        }
        // fallback: 1 query leve
        return optional(
            DocumentoTipo::query()->whereKey($this->documento_tipo_id)->first(['abreviatura'])
        )->abreviatura;
    }

    /**
     * Sigla da UF de expedição (ex.: "AP").
     */
    public function getExpedicaoEstadoSiglaAttribute(): ?string
    {
        if ($this->relationLoaded('estado')) {
            return optional($this->estado)->sigla;
        }
        return optional(
            Estado::query()->whereKey($this->expedicao_estado_id)->first(['sigla'])
        )->sigla;
    }

    /**
     * Sigla do país de expedição (ex.: "BR").
     */
    public function getExpedicaoPaisSiglaAttribute(): ?string
    {
        if ($this->relationLoaded('pais')) {
            return optional($this->pais)->sigla;
        }
        return optional(
            Pais::query()->whereKey($this->expedicao_pais_id)->first(['sigla'])
        )->sigla;
    }

    /* =========================
     |  SCOPES ÚTEIS (opcional)
     |=========================*/

    // Facilita pegar sempre o “último” documento de um tipo
    public function scopeDoTipo($q, int $tipoId)
    {
        return $q->where('documento_tipo_id', $tipoId);
    }

    public function scopeDaPessoa($q, int $pessoaId)
    {
        return $q->where('pessoa_id', $pessoaId);
    }
}
