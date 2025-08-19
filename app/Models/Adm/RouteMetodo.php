<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteMetodo extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'route_metodos';
    protected $fillable = [
        'name',
    ];
}
