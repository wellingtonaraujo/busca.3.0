<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PessoaDocumentoAntigo extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'pessoa_documento';
    protected $fillable = [
        'idpessoa_documento', // id da tabela pessoa_documento
        'idpessoa', //id da tabela pessoaq
        'iddocumento', // tabela geral_documento
        'numero_documento',
        'orgao_expedicao',
        'data_expedicao',
        'uf_documento',
        'pais_documento',
    ];

    public function getDocumentoTipoAttribute(){
        $tipo = GeralDocumento::where('iddocumento',$this->iddocumento)->first();
        return $tipo->documento;
    }

    public function getDataExpedicaoAttribute($value){
         return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function documentoTipo(){
        return $this->hasOne(GeralDocumento::class, 'iddocumento', 'iddocumento');
    }

    public function pais(){
        return $this->hasOne(GeralPais::class, 'idpais', 'pais_documento');
    }

    public function estado(){
        return $this->hasOne(GeralEstado::class, 'idestado', 'uf_documento');
    }

}
