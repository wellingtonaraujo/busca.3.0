<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PessoaContato extends Model
{
    use HasFactory;

    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoa_contatos';

    protected $fillable   = [
        'pessoa_id',
        'contato_tipo_id',
        'contato',
        'observacao',
        'nome',
        'vinculado_tipo_id',
    ];

    /* =========================
     |  RELACIONAMENTOS (FK -> belongsTo)
     |=========================*/

    public function pessoa(): BelongsTo
    {
        // contato pertence a uma pessoa
        return $this->belongsTo(Pessoa::class, 'pessoa_id', 'id');
    }

    public function contatoTipo(): BelongsTo
    {
        // tipo do contato (ex.: telefone, e-mail, whatsapp)
        return $this->belongsTo(ContatoTipo::class, 'contato_tipo_id', 'id');
    }

    public function vinculadoTipo(): BelongsTo
    {
        // vínculo/relacionamento (ex.: mãe, pai, cônjuge)
        return $this->belongsTo(VinculadoTipo::class, 'vinculado_tipo_id', 'id');
    }

    /* =========================
     |  SCOPES ÚTEIS (opcional)
     |=========================*/

    public function scopeDaPessoa($q, int $pessoaId)
    {
        return $q->where('pessoa_id', $pessoaId);
    }

    public function scopeDoTipo($q, int $contatoTipoId)
    {
        return $q->where('contato_tipo_id', $contatoTipoId);
    }

    public function scopeDoVinculo($q, int $vinculadoTipoId)
    {
        return $q->where('vinculado_tipo_id', $vinculadoTipoId);
    }
}
