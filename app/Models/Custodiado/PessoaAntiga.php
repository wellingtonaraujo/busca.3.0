<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaAntiga extends Model
{
    use HasFactory;

    protected $connection = 'siapen';
    protected $table = 'tbpessoa';
    protected $primaryKey = 'id'; // confirme se é mesmo 'id'
    public $timestamps = false;   // se essa tabela não tiver created_at/updated_at

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

    public function custodiadoAntigo()
    {
        // interno.idpessoa → tbpessoa.id
        return $this->hasOne(CustodiadoAntigo::class, 'idpessoa', 'id');
    }

    // 🔹 Quando a pessoa é o visitante
    public function vinculadosComoVisitante()
    {
        return $this->hasMany(
            VinculadoAntigo::class,
            'idvisitante', // FK em vinculado_antigos
            'id'           // PK local em pessoa_antigas
        );
    }

    // 🔹 Quando a pessoa é o interno
    public function vinculadosComoInterno()
    {
        return $this->hasManyThrough(
            VinculadoAntigo::class,
            CustodiadoAntigo::class,
            'idpessoa',   // FK em custodiado_antigos → pessoa_antigas.id
            'idinterno',  // FK em vinculado_antigos → custodiado_antigos.idinterno
            'id',         // PK local em pessoa_antigas
            'idinterno'   // PK em custodiado_antigos
        );
    }

    // 🔹 Se quiser juntar os dois papéis em um só accessor
    public function getTodosVinculadosAttribute()
    {
        return $this->vinculadosComoVisitante
            ->merge($this->vinculadosComoInterno);
    }

    // 🔹 Se quiser unificar em um só
    public function vinculadoAntigo()
    {
        return $this->vinculadosComoVisitante()->get()
            ->merge($this->vinculadosComoInterno()->get());
    }

    public function pessoaDocumentoAntigo(){
        return $this->hasMany(PessoaDocumentoAntigo::class, 'idpessoa', 'id');
    }
}
