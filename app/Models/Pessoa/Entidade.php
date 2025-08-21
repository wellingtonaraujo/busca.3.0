<?php

namespace App\Models\Pessoa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entidade extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'entidades';
    protected $fillable = [
        'nome',
        'sigla',
    ];

    public function setNomeAttribute($value){
        $this->attributes['nome'] = strtoupper($value);
    }

    public function setSiglaAttribute($value){
        $this->attributes['sigla'] = strtoupper($value);
    }
}
