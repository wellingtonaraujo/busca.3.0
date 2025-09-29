<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContatoTipo extends Model
{
    use SoftDeletes;

    protected $connection = 'siapenweb';
    protected $table      = 'contato_tipos';
    protected $fillable   = [
        'descricao'
    ];

    public function setDescricao($values){
        $this->attributes['descricao'] = strtoupper($values);
    }
}
