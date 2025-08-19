<?php

namespace Database\Seeders;

use App\Models\Adm\Route;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //semeando profile_routes
        $profiles = Profile::get();
        foreach ($profiles as $profile) {
            $routes = Route::get();
            foreach ($routes as $route) {
                $create = [
                    'profile_id' => $profile->id,
                    'route_id'   => $route->id,
                ];
            }
        }
    }
}
