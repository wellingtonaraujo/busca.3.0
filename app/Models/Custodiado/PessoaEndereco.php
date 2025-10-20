<?php

namespace App\Models\Custodiado;

use App\Classes\Strings;
use App\Models\Adm\Bairro;
use App\Models\Adm\Cidade;
use App\Models\Adm\Estado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AdminBairro;
use App\Models\AdminCidade;
use App\Models\AdminEstado;

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
        'uf_id',
        'cep',
    ];

    public function pessoa(){
        return $this->hasOne(Pessoa::class, 'id', 'pessoa_id');
    }

    public function bairro(){
        return $this->hasOne(Bairro::class, 'id', 'bairro_id');
    }

    public function cidade(){
        return $this->hasOne(Cidade::class, 'id', 'cidade_id');
    }

    public function estado(){
        return $this->hasOne(Estado::class, 'id', 'uf_id');
    }

    public function setCepAttribute($value){
        // somente numeros
        $this->attributes['cep'] = preg_replace('/\D/', '', $value);
    }

    public function setNumeroAttribute($value){
        // somente numeros
        $this->attributes['numero'] = preg_replace('/\D/', '', $value);
    }

    public function setEnderecoAttribute($value){
        $this->attributes['endereco'] = strtoupper($value);
    }

    public function setComplementoAttribute($value){
        $this->attributes['complemento'] = strtoupper($value);
    }

}
