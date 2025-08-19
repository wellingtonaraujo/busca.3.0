<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Profile;
use App\Models\Profiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Desativa temporariamente os checks de chave estrangeira (evita erro em ordem de deleção)
        // Schema::disableForeignKeyConstraints();

        // Limpa tabelas antes de popular
        // DB::table('profile_menus')->delete(); // importante fazer primeiro
        // DB::table('profiles')->delete();
        // DB::table('menus')->delete();

        // Reseta AUTO_INCREMENT para 1 (ou qualquer outro valor desejado)
        // DB::statement('ALTER TABLE profile_menus AUTO_INCREMENT = 1');
        // DB::statement('ALTER TABLE profiles AUTO_INCREMENT = 1');
        // DB::statement('ALTER TABLE menus AUTO_INCREMENT = 1');

        // Reativa os checks de chave estrangeira
        // Schema::enableForeignKeyConstraints();

        // Cria perfis
        // $admin = Profile::create(['name' => 'Admin', 'description' => 'Administrador do site']);
        // $vendedor = Profile::create(['name' => 'Vendedor', 'description' => 'Vendedor credenciado']);

        // Menu: Administração (pai)
        $administracao = Menu::create([
            'name' => 'Administração',
            'icon' => 'ti ti-settings',
            'route' => null,
            'parent_id' => null,
            'order_no' => 2,
            'is_active' => true,
        ]);

        // Submenus de Administração
        $menus = Menu::create([
            'name' => 'Menus do sistema',
            'icon' => 'ti ti-menu-2',
            'route' => '/menu',
            'parent_id' => $administracao->id,
            'order_no' => 1,
            'is_active' => true,
        ]);

        // Submenus de Administração
        $usuarios = Menu::create([
            'name' => 'Usuários',
            'icon' => 'ti ti-users',
            'route' => '/users',
            'parent_id' => $administracao->id,
            'order_no' => 2,
            'is_active' => true,
        ]);

        $perfis = Menu::create([
            'name' => 'Perfis de Usuários',
            'icon' => 'ti ti-id',
            'route' => '/profile',
            'parent_id' => $administracao->id,
            'order_no' => 2,
            'is_active' => true,
        ]);

        // Associa menus aos perfis
        $profile = Profile::first();

        $profile->menus()->attach([
            $administracao->id,
            $menus->id,
            $usuarios->id,
            $perfis->id
        ]);
    }
}
