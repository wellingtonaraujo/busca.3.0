<?php

namespace Database\Seeders;

use App\Models\Adm\RouteMetodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RouteMetodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //semeando os metodos

        $metodos = [
            ['name'=>'index'],
            ['name'=>'show'],
            ['name'=>'print'],
            ['name'=>'create'],
            ['name'=>'store'],
            ['name'=>'edit'],
            ['name'=>'update'],
            ['name'=>'destroy'],
        ];

        foreach ($metodos as $acao) {
            $created = RouteMetodo::create($acao);
        }

    }
}
