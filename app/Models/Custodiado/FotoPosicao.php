<?php

namespace App\Models\Custodiado;

use App\Classes\Strings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoPosicao extends Model
{
    use softDeletes;

    protected $connection = 'siapenweb_foto';
    protected $table = 'foto_posicoes';

    protected $fillable = [
        'descricao'
    ];

    public function setDescricaoAttribute($value) {
        $this->attributes['descricao'] = strtoupper($value);
    }
}
