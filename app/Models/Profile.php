<?php

namespace App\Models;

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
}


// Algo deu errado. Tente novamente! 42S22SQLSTATE[42S22]:
// Column not found: 1054 Unknown column 'profile_menus.profiles_id' in 'where clause' (Connection: mysql, SQL: delete from `profile_menus` where `profile_menus`.`menu_id` = 1 and `profile_menus`.`profiles_id` in (?, ?))
