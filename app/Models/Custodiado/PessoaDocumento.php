<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'frente_img',
        'frente_img_size',
        'frente_img_type',
        'verso_img',
        'verso_img_size',
        'verso_img_type',
        'data_expedicao',
        'orgao_expedicao',
        'descricao',
        'fase_persecucao_id'
    ];

    function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    private function estado()
    {
        // return $this->hasOne(Estado::class, 'id', 'expedicao_estado_id');
    }

    function pais()
    {
        // return $this->hasOne(Pais::class, 'id', 'expedicao_pais_id');
    }

    function documentoTipo()
    {
        return $this->hasOne(DocumentoTipo::class, 'id', 'documento_tipo_id');
    }

    // public function nomeEstadoExpedicao()
    // {
    //     try {
    //         $estado = $this->estado()->first();
    //         return $estado->nome;
    //     } catch (\Throwable $th) {
    //         return "INDISPONÍVEL";
    //     }
    // }
}
