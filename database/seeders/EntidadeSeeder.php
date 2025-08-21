<?php

namespace Database\Seeders;

use App\Models\Pessoa\Entidade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //migrar orgao_publicos do banco antigo para entidades do banco novo
        $orgaoPublico = DB::connection('siapenbusca')->table("orgao_publicos")->get();
        foreach ($orgaoPublico as $orgao) {
            $dados = [
                'nome' => $orgao->descricao,
                'sigla' => $orgao->sigla,
            ];

            $existeEntidade = Entidade::where($dados)->get();

            if(is_null($existeEntidade)){
                $created = Entidade::create($dados);
            }
        }
    }
}
