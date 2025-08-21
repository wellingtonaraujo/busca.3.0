<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Adm\ProfileRoute;
use App\Models\Adm\Route;
use App\Models\Adm\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpKernel\Profiler\Profile;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_id',
        'user_status_id',
        'entidade_atual_id',
        'pessoa_id',
        'cpf',
        'departamento',
        'funcao',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     *
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function userStatus(){
        return $this->hasOne(UserStatus::class, 'id', 'user_status_id');
    }

    public function routeAccess($route_name){
        if(Auth::user()->profile_id == 1) return true;

        $route = Route::where('name', $route_name)->first();

        $existsPermission = ProfileRoute::where('profile_id', Auth::user()->profile_id)->where('route_id', $modelRoute->id)->first();

        return !is_null($existsPermission) ? true : false;
    }
}
