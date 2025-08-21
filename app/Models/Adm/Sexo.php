<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sexo extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'sexos';
    protected $fillable = [
        'descricao',
    ];

    public function setSexoAttribute($value){
        $this->attributes['sexo'] = strtoupper($value);
    }
}
