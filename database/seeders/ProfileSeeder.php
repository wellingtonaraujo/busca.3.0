<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //semear profiles
        $perfil = [
            'name'      => 'Administrador',
            'descricao' => 'Administrador geral do sistema',
        ];

        $created = Profile::create($perfil);
    }
}
