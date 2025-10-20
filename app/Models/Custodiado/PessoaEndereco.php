<?php

namespace App\Models\Custodiado;

use App\Models\Adm\Bairro;
use App\Models\Adm\Cidade;
use App\Models\Adm\Estado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PessoaEndereco extends Model
{
    use SoftDeletes;

    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoa_enderecos';

    protected $fillable   = [
        'pessoa_id',
        'endereco',
        'numero',
        'complemento',
        'bairro_id',
        'cidade_id',
        'uf_id',     // mantém seu padrão atual
        'cep',
    ];

    /* =========================
     |  RELACIONAMENTOS (FK -> belongsTo)
     |=========================*/

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id', 'id');
    }

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class, 'bairro_id', 'id');
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'cidade_id', 'id');
    }

    /** UF/Estado (sua coluna é uf_id) */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'uf_id', 'id');
    }

    /* =========================
     |  MUTATORS (sanitização)
     |=========================*/

    public function setCepAttribute($value): void
    {
        $v = is_string($value) ? $value : (string) $value;
        $digits = preg_replace('/\D+/', '', $v ?? '');
        $this->attributes['cep'] = $digits ?: null;
    }

    public function setNumeroAttribute($value): void
    {
        // aceita vazio (ex.: s/n)
        if ($value === null || $value === '') {
            $this->attributes['numero'] = null;
            return;
        }
        $v = is_string($value) ? $value : (string) $value;
        $digits = preg_replace('/\D+/', '', $v);
        $this->attributes['numero'] = $digits ?: null;
    }

    public function setEnderecoAttribute($value): void
    {
        $this->attributes['endereco'] = $value !== null
            ? mb_strtoupper((string) $value, 'UTF-8')
            : null;
    }

    public function setComplementoAttribute($value): void
    {
        $this->attributes['complemento'] = $value !== null
            ? mb_strtoupper((string) $value, 'UTF-8')
            : null;
    }

    /* =========================
     |  ACCESSORS ÚTEIS
     |=========================*/

    /** CEP formatado (00000-000) */
    public function getCepFormatadoAttribute(): ?string
    {
        $cep = $this->attributes['cep'] ?? null;
        if (!$cep) return null;

        $d = preg_replace('/\D+/', '', (string) $cep);
        if (strlen($d) !== 8) return $cep;

        return substr($d, 0, 5) . '-' . substr($d, 5, 3);
    }

    /** Texto pronto para impressão */
    public function getLinhaCompletaAttribute(): ?string
    {
        $partes = array_filter([
            $this->endereco,
            $this->numero ? 'Nº ' . $this->numero : null,
            $this->complemento,
            optional($this->bairro)->nome,
            optional($this->cidade)->nome,
            optional($this->estado)->sigla,
            $this->cep_formatado,
        ]);

        return $partes ? implode(', ', $partes) : null;
    }

    /* =========================
     |  SCOPES (opcionais)
     |=========================*/

    public function scopeDaPessoa($q, int $pessoaId)
    {
        return $q->where('pessoa_id', $pessoaId);
    }

    public function scopeCompletos($q)
    {
        return $q->whereNotNull('endereco')->whereNotNull('cidade_id')->whereNotNull('uf_id');
    }
}
