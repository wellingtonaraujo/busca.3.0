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
        // limpar a tabela
        $truncated = Entidade::truncate();

        //migrar orgao_publicos do banco antigo para entidades do banco novo
        $orgaoPublico = DB::connection('siapenbusca')->table("orgao_publicos")->get();
        foreach ($orgaoPublico as $orgao) {
            $nome =  $orgao->descricao == "Secretaria de Estado de Justiça e Segurança Public" ? "SECRETARIA DE ESTADO DE JUSTIçA E SEGURANçA PÚBLICA" : strtoupper($orgao->descricao);
            $dados = [
                'nome' => $nome,
                'sigla' => $orgao->sigla,
            ];

            $existeEntidade = Entidade::where('nome',$dados['nome'])->first();

            if(is_null($existeEntidade)){
                $existeEntidade = Entidade::where('sigla',$dados['sigla'])->first();
                if(is_null($existeEntidade)) $created = Entidade::create($dados);
            }
        }
    }
}
