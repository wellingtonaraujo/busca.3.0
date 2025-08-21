<?php

namespace App\Models\Pessoa;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'pessoas';
    protected $fillable = [
        'nome',
        'cpf',
        'entidade_id',
        'matricula',
        'nascimento',
        'sexo_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'cidade',
        'uf',
        'email',
        'celular',
        'foto_perfil', // <- armazenará a foto binária ou base64
    ];

    public function entidade()
    {
        return $this->hasOne(Entidade::class, 'id', 'entidade_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'pessoa_id', 'id');
    }
}
