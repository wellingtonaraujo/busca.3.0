<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acl extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'acls';
    protected $fillable = [
        'name',
        'descricao',
    ];

    public function getRoutes($acl_name){
        return Route::where('name', 'like', "$acl_name.%")->get();
    }
}
