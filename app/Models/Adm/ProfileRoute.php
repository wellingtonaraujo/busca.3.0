<?php

namespace App\Models\Adm;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileRoute extends Model
{
    use HasFactory; use SoftDeletes;
    protected $table = 'profile_routes';
    protected $fillable = [
        'profile_id',
        'route_id',
    ];

    public function route(){
        return $this->hasOne(Route::class, 'id', 'route_id');
    }

    public function profile(){
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }
}
