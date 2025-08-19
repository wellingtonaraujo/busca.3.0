<?php

namespace Database\Seeders;

use App\Models\Adm\Acl;
use App\Models\Adm\Route;
use App\Models\Adm\RouteMetodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar as rotas
        $result = Route::truncate();
        //semeando routes
        $acls = Acl::get();

        foreach ($acls as $acl) {
            $metodos = RouteMetodo::get();
            foreach ($metodos as $metodo) {
                $create = [
                    'name' => "$acl->name.$metodo->name",
                ];

                $created = Route::create($create);
            }
        }
    }
}
