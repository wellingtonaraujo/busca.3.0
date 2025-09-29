<?php

use App\Models\Custodiado\PessoaContato;
use App\Models\Custodiado\PessoaDocumento;

if(!function_exists('ultimoContato')){
    function ultimoContato($pessoa_id){
        return PessoaContato::where('pessoa_id', $pessoa_id)->latest('created_at')->first();
    }
}

if(!function_exists('getDocumentos')){
    function getDocumentos($pessoa_id){
        return PessoaDocumento::where('pessoa_id', $pessoa_id)
            ->whereIn('documento_tipo_id', [1,2])
            ->orderBy('documento_tipo_id', 'desc')
            ->get();
    }
}
