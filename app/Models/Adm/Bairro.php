<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Adm\Cidade;

class Bairro extends Model
{
    use SoftDeletes;
    /*
    	@description nome da tabela do banco
     */
    protected $connection = 'mysql';
    protected $table = 'bairros';
    /*
    	campos da tabela
     */
    protected $fillable = [
        'idbairro',
        'cidade_id',
    	'nome',
    ];

    public function cidade(){
        return $this->hasOne(Cidade::class, 'id', 'cidade_id');
    }

    public function setNomeAttribute($value){
        $this->attributes['nome'] = strtoupper($value);
    }
}
