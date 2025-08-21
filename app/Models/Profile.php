<?php

namespace App\Models;

use App\Models\Pessoa\Entidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profiles';
    protected $fillable = [
        'name',
        'descricao',
        'entidade_id',
        'expira',
    ];

    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'profile_menus');
    }

    public function entidade(){
        return $this->hasOne(Entidade::class, 'id', 'entidade_id');
    }
}
