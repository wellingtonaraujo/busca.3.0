<?php

namespace App\Traits;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\Pessoa;
use App\Models\Custodiado\PessoaAntiga;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait SearchTrait
{
    public $executionTime;

    public function situacaoAtualOpcoes(): array
    {
        return DB::connection('siapen')
            ->table('vwcustodiado_status')
            ->orderBy('id')
            ->pluck('descricao', 'id')
            ->toArray();
    }

    public function regimeOpcoes(): array
    {
        return DB::connection('siapenweb_dp')
            ->table('regimes')
            ->orderBy('id')
            ->pluck('descricao', 'id')
            ->toArray();
    }

    public function search(Request $request)
    {
        // retorna null se a $request->all() retiver vazia;
        if ($this->requestEmpty($request)) {
            return null;
        }

        $pessoas = $this->getPeople($request);
        return $pessoas;
    }

    private function requestEmpty(Request $request)
    {
        // se a request->all() estiver vazia []
        if (empty(array_filter($request->all()))) return true;

        // se todas as chaves, exceto documento_tipo_id, estiverem nulas
        $data = $request->except('documento_tipo_id');

        $allEmpty = collect($data)->every(function ($value) {
            return is_null($value) || $value === '';
        });

        if ($allEmpty) {
            // caso estejam vazias ou nulas
            return true;
        }

        // caso haja conteudo na request->all()
        return false;
    }

    // private function getPeople(Request $request)
    // {
    //     $start = microtime(true); // inicia cronômetro

    //     $query = Pessoa::query()->select('pessoas.*');

    //     // Join condicional para documentos
    //     if ($request->filled('documento_tipo_id') || $request->filled('documento_numero')) {
    //         $query->join('pessoa_documentos as pd', function ($join) {
    //             $join->on('pessoas.id', '=', 'pd.pessoa_id');
    //         })
    //             ->addSelect([
    //                 'pd.pessoa_id',
    //                 'pd.documento_tipo_id',
    //                 'pd.documento_numero',
    //             ]);

    //         if ($request->filled('documento_tipo_id')) {
    //             $query->where('pd.documento_tipo_id', $request->documento_tipo_id);
    //         }

    //         if ($request->filled('documento_numero')) {
    //             $query->where('pd.documento_numero', $request->documento_numero);
    //         }
    //     }

    //     // Join condicional para contatos
    //     if ($request->filled('contato') || $request->filled('contato_nome')) {
    //         $query->join('pessoa_contatos as pc', 'pessoas.id', '=', 'pc.pessoa_id');

    //         if ($request->filled('contato')) {
    //             $query->where('pc.contato', 'like', "%{$request->contato}%");
    //         }

    //         if ($request->filled('contato_nome')) {
    //             $query->where('pc.nome', 'like', "%{$request->contato_nome}%");
    //         }
    //     }

    //     // Filtro por nome
    //     if ($request->filled('nome')) {
    //         $query->where('pessoas.nome', 'like', "%{$request->nome}%");
    //     }

    //     // Filtro por apelido
    //     if ($request->filled('apelido')) {
    //         $query->where('pessoas.alcunha', 'like', "%{$request->apelido}%");
    //     }

    //     // Ordenação (default: nome)
    //     $orderBy = $request->order_by ?? 'pessoas.nome';
    //     $query->orderBy($orderBy);

    //     // Evita duplicados por conta dos joins
    //     $query->distinct();

    //     // Execução
    //     $pessoas = $query->get();

    //     $end = microtime(true);
    //     $executionTime = number_format($end - $start, 4);

    //     $this->executionTime = $executionTime;

    //     return $pessoas;
    // }

    private function getPeople(Request $request)
    {
        $start = microtime(true);

        // ========================
        // 1️⃣ Buscar pessoas novas
        // ========================
        $queryPessoa = Pessoa::query()
            ->select('pessoas.*', DB::raw("'nova' as origem"));

        if ($request->filled('nome')) {
            $queryPessoa->where('pessoas.nome', 'like', "%{$request->nome}%");
        }

        if ($request->filled('apelido')) {
            $queryPessoa->where('pessoas.alcunha', 'like', "%{$request->apelido}%");
        }

        $pessoasNova = $queryPessoa->get();

        // ========================
        // 2️⃣ Buscar pessoas antigas (não importadas)
        // ========================
        $pessoasAtuaisIds = $pessoasNova->pluck('id')->toArray();

        $queryPessoaAntiga = PessoaAntiga::query()
            ->select('tbpessoa.*', DB::raw("'antiga' as origem"));

        if (count($pessoasAtuaisIds)) {
            $queryPessoaAntiga->whereNotIn('tbpessoa.id', $pessoasAtuaisIds);
        }

        if ($request->filled('nome')) {
            $queryPessoaAntiga->where('tbpessoa.nome', 'like', "%{$request->nome}%");
        }

        if ($request->filled('apelido')) {
            $queryPessoaAntiga->where('tbpessoa.alcunha', 'like', "%{$request->apelido}%");
        }

        $pessoasAntiga = $queryPessoaAntiga->get();

        // ========================
        // 3️⃣ União das pessoas (nova antes da antiga)
        // ========================
        $pessoas = $pessoasNova->concat($pessoasAntiga)->values();
        $pessoasIds = $pessoas->pluck('id')->toArray();

        // ========================
        // 4️⃣ Buscar documentos (somente campos essenciais)
        // ========================
        $documentos = DB::connection('siapenweb_dp')
            ->table('pessoa_documentos')
            ->select('pessoa_id', 'documento_tipo_id', 'documento_numero') // campos essenciais
            ->whereIn('pessoa_id', $pessoasIds)
            ->when($request->filled('documento_tipo_id'), fn($q) => $q->where('documento_tipo_id', $request->documento_tipo_id))
            ->when($request->filled('documento_numero'), fn($q) => $q->where('documento_numero', $request->documento_numero))
            ->get()
            ->groupBy('pessoa_id');

        // ========================
        // 5️⃣ Buscar contatos (somente campos essenciais)
        // ========================
        $contatos = DB::connection('siapenweb_dp')
            ->table('pessoa_contatos')
            ->select('pessoa_id', 'contato', 'nome') // campos essenciais
            ->whereIn('pessoa_id', $pessoasIds)
            ->when($request->filled('contato'), fn($q) => $q->where('contato', 'like', "%{$request->contato}%"))
            ->when($request->filled('contato_nome'), fn($q) => $q->where('nome', 'like', "%{$request->contato_nome}%"))
            ->get()
            ->groupBy('pessoa_id');

        // ========================
        // 6️⃣ Anexar documentos e contatos às pessoas
        // ========================
        $pessoas = $pessoas->map(function ($pessoa) use ($documentos, $contatos) {
            $pessoa->documentos = $documentos->get($pessoa->id, collect());
            $pessoa->contatos = $contatos->get($pessoa->id, collect());
            return $pessoa;
        });

        // ========================
        // 7️⃣ Ordenação final: nova antes da antiga + campo solicitado
        // ========================
        $orderBy = $request->order_by ?? 'nome';

        $pessoas = $pessoas->sortBy([
            fn($p) => $p->origem === 'nova' ? 0 : 1, // nova antes
            $orderBy,
        ])->values();

        $end = microtime(true);
        $this->executionTime = number_format($end - $start, 4);

        return $pessoas;
    }
}
