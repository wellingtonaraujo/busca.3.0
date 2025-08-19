<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus';
    protected $fillable = [
        'name',
        'icon',
        'route',
        'parent_id',
        'order_no',
        'is_active',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->where('is_active', true)->orderBy('order_no');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function existeProfileMenu($menu_id)
    {
        $exist = ProfileMenu::where("menu_id", $menu_id)->where("profile_id", Auth::user()->profile_id)->first();
        return is_null($exist) ? false : true;
    }

    public function profileMenu()
    {
        return $this->hasMany(ProfileMenu::class, 'menu_id', 'id');
    }

    public function Profile()
    {
        return $this->belongsToMany(Profile::class, 'profile_menus');
    }
}
