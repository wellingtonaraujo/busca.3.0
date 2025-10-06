<?php

namespace App\Models\Custodiado;

use App\Models\Adm\Estado;
use App\Models\Adm\Pais;
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
        'data_expedicao',
        'orgao_expedicao',
        'descricao',
    ];

    function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function getDocumentoTipoAttribute(){
        $tipo = DocumentoTipo::find($this->documento_tipo_id);
        return $tipo->abreviatura;
    }

    public function  getExpedicaoEstadoAttribute(){
        $estado = Estado::find($this->expedicao_estado_id);
        return optional($estado)->sigla;
    }

    public function getExpedicaoPaisAttribute(){
        $pais = Pais::find($this->expedicao_pais_id);
        return optional($pais)->sigla;
    }

    // public function estado()
    // {
    //     return $this->hasOne(Estado::class, 'id', 'expedicao_estado_id');
    // }

    // public function pais()
    // {
    //     return $this->hasOne(Pais::class, 'id', 'expedicao_pais_id');
    // }

    public function documentoTipo()
    {
        return $this->hasOne(DocumentoTipo::class, 'id', 'documento_tipo_id');
    }
}
