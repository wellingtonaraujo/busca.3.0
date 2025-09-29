<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\Strings;

class VinculadoTipo extends Model
{
    use SoftDeletes;

    protected $connection = 'siapenweb_dp';
    protected $table = 'vinculado_tipos';

    protected $fillable = [
    	'descricao',
    ];

    public function setDescricaoAttribute($value){
        $this->attributes['descricao'] = Strings::maiuscula($value);
    }
}
