<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculadoStatus extends Model
{
    use SoftDeletes;

    protected $connection = 'siapenweb_dp';
    protected $table = 'vinculado_statuses';

    protected $fillable = [
    	'descricao',
    ];

    public function setDescricaoAttribute($value){
        $this->attributes['descricao'] = strtoupper($value);
    }

}
