<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatus extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'user_statuses';

    protected $fillable = [
        'descricao',
    ];

    public function setDescricaoAttribute($value)
    {
        $this->attributes['descricao'] = strtoupper($value);
    }
}
