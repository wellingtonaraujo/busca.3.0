<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VinculadoCustodiado extends Model
{
    use HasFactory;

    protected $connection = 'siapenweb_dp';
    protected $table = 'vinculado_custodiados';

    protected $fillable = [
        'vinculado_custodiado_status_id',
    	'vinculado_pessoa_id',
        'custodiado_pessoa_id',
        'vinculo_tipo_ida_id',
        'vinculo_tipo_volta_id',
        'vinculado_custodiado_dias_id',
        'obs_cartao',
        'visita_virtual',
        'idinterno_visitante' //Tabela Antiga
    ];

    function custodiado(){
        return $this->hasOne(Custodiado::class, 'id','custodiado_pessoa_id');
    }
}
