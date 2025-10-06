<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb';
    protected $table = 'paises';
    protected $fillable = [
        'pais_id',
        'nome',
        'sigla',
    ];
}
