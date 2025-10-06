<?php

use App\Models\Custodiado\DocumentoTipo;
use App\Models\Custodiado\GeralDocumento;
use App\Models\Custodiado\PessoaContato;
use App\Models\Custodiado\PessoaDocumento;
use App\Models\Custodiado\PessoaDocumentoAntigo;

if (!function_exists('ultimoContato')) {
    function ultimoContato($pessoa_id)
    {
        return PessoaContato::where('pessoa_id', $pessoa_id)->latest('created_at')->first();
    }
}

if (!function_exists('getDocumento')) {
    function getDocumento($documento, $origem)
    {
        if ($origem == 'nova') {
            $doc = PessoaDocumento::where('documento_numero', $documento->documento_numero)
                ->select(
                    'pessoa_id',
                    'documento_tipo_id',
                    'expedicao_estado_id',
                    'expedicao_pais_id',
                    'documento_numero',
                    'data_expedicao',
                    'orgao_expedicao',
                    'descricao',
                )
                ->first();

            return $doc->documento_tipo
                . " " . ($doc->documento_numero ?? '')
                . " " . ($doc->expedicao_estado ?? '')
                . " " . ($doc->expedicao_pais ?? '');
        } else {
            $doc = PessoaDocumentoAntigo::where('numero_documento', $documento->documento_numero)->first();
            return $doc->documento_tipo . $documento->documento_numero . " " . optional($doc->estado)->sigla . " " . optional($doc->pais)->sigla;
        }
    }
}
