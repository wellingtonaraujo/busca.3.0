<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
{
    use SoftDeletes;
    /*
    	@description nome da tabela do banco
     */
    protected $connection = 'siapenweb';
    protected $table = 'cidades';
    /*
    	campos da tabela
     */
    protected $fillable = [
        'idcidade',
        'estado_id',
    	'nome',
        'descricao',
        'sigla',
    ];

    public function estado(){
        return $this->hasOne(Estado::class, 'id', 'estado_id');
    }
}
