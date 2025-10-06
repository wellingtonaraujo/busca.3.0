<?php

namespace App\Models\Custodiado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeralStatus extends Model
{
    use HasFactory;
    protected $connection = 'siapen';
    protected $table = 'geral_status';

    protected $fillable = [
        'idstatus',
        'status',
    ];
}
