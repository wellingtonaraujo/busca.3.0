<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //semear profiles
        // $perfil = [
        //     'name'      => 'Administrador',
        //     'descricao' => 'Administrador geral do sistema',
        // ];

        $profileOld = DB::connection('siapenbusca')->table('profiles')->whereNotNull('orgao_publico_id')->get();

        foreach ($profileOld as $item) {
            $dados = [
                'name'        => $item->name,
                'descricao'   => null,
                'entidade_id' => $item->orgao_publico_id,
                'expira'      => $item->numero_dias_expiracao,
            ];
            $created = Profile::create($dados);
        }
    }
}
