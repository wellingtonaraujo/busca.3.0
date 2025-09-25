<?php

namespace App\Models\Custodiado;

use App\Classes\Datas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Image;

class Pessoa extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb_dp';
    protected $table      = 'pessoas';
    /*
    	campos da tabela
     */
    protected $fillable   = [
        //dados pessoais
        'nome',
        'homonimo',
        'nome_social',
        'usar_social',
        'alcunha',
        'nascimento',
        'pais_id',
        'estado_id',
        'natural_id',
        'sexo_id',
        'mae',
        'pai',
        //dados gerais
        'cutis_id',
        'cor_olhos_id',
        'altura',
        'cor_cabelo_id',
        'religiao_id',
        'escolaridade_id',
        'estado_civil_id',
        'pne_id',
        'profissao_id',
        'tribo_indigena_id',
        'orientacao_sexual_id',
        'identidade_generos_id',
        //identidade fotografica
        'img',
        'img_type',
        'img_size',
        'principal_pessoa_foto_id',
        //id do banco antigo
        'idpessoa'
    ];

    public function contatos()
    {
        return $this->hasMany(PessoaContato::class, 'pessoa_id', 'id');
    }

    public function documentos()
    {
        return $this->hasMany(PessoaDocumento::class, 'pessoa_id', 'id');
    }

    public function custodiado()
    {
        return $this->hasOne(Custodiado::class, 'pessoa_id', 'id');
    }

    public function vinculado()
    {
        return $this->hasOne(Vinculado::class, 'vinculado_pessoa_id', 'id');
    }

    public function vinculadoCustodiado()
    {
        // return $this->hasMany(VinculadoCustodiado::class, 'vinculado_pessoa_id', 'id');
    }

    public function pessoaCondutaDisciplinar()
    {
        // return $this->hasMany(PessoaCondutaDisciplinar::class, 'pessoa_id', 'id')
        // ->with('condutaDisciplinarTipo', 'condutaDisciplinarTipoPenal', 'condutaDisciplinarStatus', 'resultado')
        // ->orderBy('id', 'desc');
    }

    public function cpf()
    {
        $pessoaDocumento = PessoaDocumento::select('documento_numero')
            ->where('pessoa_id', '=', $this->id)
            ->where('documento_tipo_id', '=', 2)
            ->orderBy('id', 'desc')
            ->first();
        if (!is_null($pessoaDocumento)) return maskcpf($pessoaDocumento->documento_numero);
        return null;
    }

    public function rg()
    {
        $pessoaDocumento = PessoaDocumento::select('documento_numero')
            ->where('pessoa_id', '=', $this->id)
            ->where('documento_tipo_id', '=', 1)
            ->orderBy('id', 'desc')
            ->first();
        if (!is_null($pessoaDocumento)) return $pessoaDocumento->documento_numero;
        return null;
    }

    // public function foto()
    // {
    //     $fotos = PessoaFoto::where('pessoa_id', $this->id)->get();

    //     if ($fotos->count() > 0) {
    //         $fotoPerfil = PessoaFoto::where('pessoa_id', $this->id)
    //             ->where('foto_tipo_id', 1)
    //             ->orWhere(function ($query) {
    //                 $query->where('pessoa_id', $this->id)
    //                     ->where('foto_posicao_id', 1);
    //             })
    //             ->orderBy('id', 'desc')
    //             ->first();
    //         if (!is_null($fotoPerfil)) {
    //             $foto = "data:" . $fotoPerfil->img_type . ";base64," . $fotoPerfil->img;
    //             return $foto;
    //         } else {
    //             return null;
    //         }
    //     }

    //     return null;
    // }
}
