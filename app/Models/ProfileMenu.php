<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profile_menus';

    protected $fillable = [
        'profile_id',
        'menu_id',
        'can_view',
    ];

    public function profile(){
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }
}
