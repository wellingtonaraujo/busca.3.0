<?php

namespace Database\Seeders;

use App\Models\Adm\Acl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //semeando acls

        $acls = [
            ['name' => 'acl','descricao'=>'Controle de acesso ao sistema'],
            ['name' => 'menu','descricao'=>'Menus do sistema'],
            ['name' => 'profile','descricao'=>'Perfís de usuários'],
            ['name' => 'profileMenu','descricao'=>'Perfil de acesso ao menu'],
            ['name' => 'route','descricao'=>'Rotas de acesso ao sistema'],
            ['name' => 'profileRoute','descricao'=>'Perfil de acesso para rotas'],
            ['name' => 'routeMetodo','descricao'=>'Métodos / Ação das rotas'],
            ['name' => 'user','descricao'=>'Usuários do sistema'],
        ];

        foreach ($acls as $acl) {
            $created = Acl::create($acl);
        }
    }
}
