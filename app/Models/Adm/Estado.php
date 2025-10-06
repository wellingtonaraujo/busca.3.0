<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $connection = 'siapenweb';
    protected $table = 'estados';
    protected $fillable = [
        'idestado',
        'pais_id',
        'nome',
        'sigla',
    ];
}
