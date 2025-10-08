<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternoVisitante extends Model
{
    use HasFactory;

    protected $connection = 'siapen';
    protected $table = 'interno_visitante';
    protected $fillable = [
        'idinterno_visitante',
        'idinterno',
        'idvisitante', // referesse ao idpessoa
        'idstatus',
    ];

    public function custodiadoAntigo(){
        return $this->hasOne(CustodiadoAntigo::class, 'idinterno', 'idinterno');
    }

    public function pessoaAntiga(){
        return $this->hasOne(PessoaAntiga::class, 'id', 'idvisitante');
    }

    public function geralStatus(){
        return $this->hasOne(GeralStatus::class, 'idstatus', 'idstatus');
    }
}
