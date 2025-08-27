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
        $profileOld = DB::connection('siapenbusca')->table('profiles')->whereNotNull('orgao_publico_id')->get();

        foreach ($profileOld as $item) {
            $dados = [
                'name'           => $item->name,
                'descricao'      => null,
                'entidade_id'    => $item->orgao_publico_id,
                'expiracao_adm'  => $item->numero_dias_expiracao,
                'expiracao_user' => 30
            ];
            $created = Profile::create($dados);
        }
    }
}
