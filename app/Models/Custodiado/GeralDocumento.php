<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralDocumento extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_documento';
    protected $fillable = [
        'iddocumento',
        'docuemto',
    ];
}
