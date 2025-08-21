<?php

namespace Database\Seeders;

use App\Models\Pessoa\Pessoa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PessoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Migrar todos os usuários de user do banco antigo e dividi-los entre as tabelas pessoa e user do novo banco
        // carrega users do banco antigo
        $userOlds = DB::connection('siapenbusca')->table('users')->get();

        $contador = 0;

        // varre a tabela users dividindo os dados
        foreach ($userOlds as $userOld) {
            $contador++;
            // cria a pessoa
            $pessoa = [
                'nome'        => $userOld->name,
                'cpf'         => $userOld->cpf,
                'entidade_id' => $userOld->orgao_publico_id,
            ];

            $existePessoa = Pessoa::where('nome', $userOld->name)->first();

            if (is_null($existePessoa)) {
                $createdPessoa = Pessoa::create($pessoa);
            }

            // cria o usuário
            $profile = DB::connection('siapenbusca')->table('user_profiles')->where('user_id', $userOld->id)->first();

            $user = [
                'profile_id'        => $profile->id,
                'user_status_id'    => $userOld->id == 1 ? 1 : 2,
                'entidade_atual_id' => $userOld->orgao_publico_id,
                'pessoa_id'         => $createdPessoa->id,
                'cpf'               => $userOld->cpf,
                'departamento'      => $userOld->departamento,
                'funcao'            => $userOld->funcao,
                'password'          => $userOld->id == 1 ? 'wellington@iapen' : '123456',
            ];

            $existeUser = User::where('pessoa_id')->first();

            if(is_null($existeUser)){
                $createdUser = User::create($user);
            }

            echo "\rLoop atual: " . $contador . " user_id: " . $userOld->id . " Total de usuarios: " . $userOlds->count();
        }
    }
}
