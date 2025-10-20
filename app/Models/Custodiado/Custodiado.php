<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /* =======================================================
     |  RELACIONAMENTOS (FK -> belongsTo)
     |=======================================================*/

    /** Custodiado pertence a uma Pessoa */
    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id', 'id');
    }

    /** Custodiado pertence a um Regime */
    public function regime(): BelongsTo
    {
        return $this->belongsTo(Regime::class, 'regime_id', 'id');
    }

    /** Situação Atual do Custodiado */
    public function situacaoAtual(): BelongsTo
    {
        // nome da classe conforme seu projeto
        return $this->belongsTo(CustodiadoSituacaoAtual::class, 'custodiado_situacao_atual_id', 'id');
    }

    /* =======================================================
     |  ACCESSORS LEVES (PROXIES)
     |=======================================================*/

    /**
     * Foto em data URI (se houver). Leve: apenas proxia a Pessoa.
     * Fallback para no_image.png deve ser feito na Blade:
     * <img src="{{ $custodiado->foto ?: asset('assets/images/icons/no_image.png') }}">
     */
    public function getFotoAttribute(): ?string
    {
        return optional($this->pessoa)->foto_base64;
    }

    /**
     * Endereço pronto (linha única), proxia PessoaEndereco::linha_completa.
     * Use preferencialmente $custodiado->pessoa->endereco->linha_completa diretamente.
     */
    public function getEnderecoAttribute(): ?string
    {
        $end = optional($this->pessoa)->endereco;
        return $end ? $end->linha_completa : null;
    }

    /**
     * Atalho opcional para CPF (formatação na model Pessoa).
     * Pode remover se quiser forçar uso de $custodiado->pessoa->cpf.
     */
    public function getCpfAttribute(): ?string
    {
        return optional($this->pessoa)->cpf;
    }

    /**
     * Atalho opcional para RG (com UF), também delega à Pessoa.
     */
    public function getRgAttribute(): ?string
    {
        return optional($this->pessoa)->rg;
    }

    // Mostra só o REGIME (com fallback)
    public function getRegimeDescricaoAttribute(): string
    {
        return $this->regime?->descricao ?? '—';
    }

    // Mostra só a SITUAÇÃO ATUAL (com fallback)
    public function getSituacaoAtualDescricaoAttribute(): string
    {
        return $this->situacaoAtual?->descricao ?? '—';
    }

    // (Opcional) Mostra os dois juntos, quando fizer sentido
    public function getRegimeESituacaoRotuloAttribute(): string
    {
        return collect([
            $this->regime?->descricao,
            $this->situacaoAtual?->descricao,
        ])->filter()->implode(' / ');
    }
}
