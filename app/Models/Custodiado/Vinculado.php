<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vinculado extends Model
{
    use HasFactory;

    protected $connection = 'siapenweb_dp';
    protected $table = 'vinculados';
    protected $fillable = [
        'vinculado_pessoa_id',
        'vinculado_status_id',
        'vinculado_caracteristicas'
    ];

    function pessoa()
    {
        return $this->hasOne(Pessoa::class, 'id', 'vinculado_pessoa_id');
    }

    function vinculadoCustodiado(){
        return $this->hasOne(VinculadoCustodiado::class, 'vinculado_pessoa_id', 'vinculado_pessoa_id');
    }

    function vinculadoStatus(){
        return $this->hasOne(VinculadoStatus::class,'id', 'vinculado_status_id');
    }
}
