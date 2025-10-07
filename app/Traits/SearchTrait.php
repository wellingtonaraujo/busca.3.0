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
    public $documentosAntigos;
    public $documentosNovos;
    public $contatosNovos;
    public $contatosAntigos;

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

        // pega todos os campos da request
        $data = $request->all();

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
    //     $start = microtime(true);

    //     // 1️⃣ Pessoas novas
    //     $pessoasNova = Pessoa::query()
    //         ->select('pessoas.*', DB::raw("'nova' as origem"))
    //         ->when($request->filled('nome'), fn($q) => $q->where('pessoas.nome', 'like', "%{$request->nome}%"))
    //         ->when($request->filled('apelido'), fn($q) => $q->where('pessoas.alcunha', 'like', "%{$request->apelido}%"))
    //         ->get()
    //         ->map(fn($p) => tap($p, fn($pessoa) => $pessoa->id_formatado = $pessoa->id . 'N'));

    //     // 2️⃣ Pessoas antigas
    //     $nomesNovas = $pessoasNova->pluck('nome')->toArray();

    //     $pessoasAntiga = PessoaAntiga::query()
    //         ->from('siapen.tbpessoa as tbpessoa')
    //         ->select('tbpessoa.*', DB::raw("'antiga' as origem"))
    //         ->when(!empty($nomesNovas), fn($q) => $q->whereNotIn('tbpessoa.nome', $nomesNovas))
    //         ->when(
    //             $request->filled('nome'),
    //             fn($q) =>
    //             $q->where('tbpessoa.nome', 'like', "%{$request->nome}%")
    //         )
    //         // 🔍 Apenas se a requisição tiver 'apelido', faz o join com interno
    //         ->when($request->filled('apelido'), function ($q) use ($request) {
    //             $q->join('siapen.interno as interno', 'interno.idpessoa', '=', 'tbpessoa.id')
    //                 ->addSelect('interno.alcunha')
    //                 ->where('interno.alcunha', 'like', "%{$request->apelido}%");
    //         })
    //         ->get()
    //         ->map(fn($p) => tap($p, fn($pessoa) => $pessoa->id_formatado = $pessoa->id . 'A'));


    //     // 3️⃣ Junta pessoas
    //     $pessoas = $pessoasNova->concat($pessoasAntiga)->values();

    //     // 4️⃣ IDs separados
    //     $idsNovas   = $pessoasNova->pluck('id')->toArray();
    //     $idsAntigas = $pessoasAntiga->pluck('id')->toArray();

    //     // 5️⃣ Documentos novas
    //     $documentosNovas = DB::connection('siapenweb_dp')
    //         ->table('pessoa_documentos')
    //         ->select('pessoa_id', 'documento_tipo_id', 'documento_numero')
    //         ->whereIn('pessoa_id', $idsNovas)
    //         ->when($request->filled('documento_numero'), fn($q) => $q->where('documento_numero', 'like', "%$request->documento_numero%"))
    //         ->whereNotNull('documento_numero')
    //         ->get()
    //         ->groupBy('pessoa_id');

    //     // 6️⃣ Documentos antigas
    //     $documentosAntigas = DB::connection('siapen')
    //         ->table('pessoa_documento')
    //         ->select('idpessoa', 'iddocumento as documento_tipo_id', 'numero_documento as documento_numero', 'orgao_expedidor', 'data_expedicao', 'uf_documento', 'pais_documento')
    //         ->whereIn('idpessoa', $idsAntigas)
    //         ->when($request->filled('documento_numero'), fn($q) => $q->where('numero_documento', $request->documento_numero))
    //         ->whereNotNull('numero_documento')
    //         ->get()
    //         ->groupBy('idpessoa');

    //     // 7️⃣ Contatos novas
    //     $contatosNovas = DB::connection('siapenweb_dp')
    //         ->table('pessoa_contatos')
    //         ->select('pessoa_id', 'contato', 'nome')
    //         ->whereIn('pessoa_id', $idsNovas)
    //         ->when($request->filled('contato'), fn($q) => $q->where('contato', 'like', "%{$request->contato}%"))
    //         ->get()
    //         ->groupBy('pessoa_id');

    //     // 8️⃣ Contatos antigas
    //     $contatosAntigas = DB::connection('siapen')
    //         ->table('pessoa_contato')
    //         ->select('idpessoa as pessoa_id', 'idtipo_contato as contato_tipo_id', 'contato')
    //         ->whereIn('idpessoa', $idsAntigas)
    //         ->when($request->filled('contato'), fn($q) => $q->where('contato', 'like', "%{$request->contato}%"))
    //         ->get()
    //         ->groupBy('pessoa_id');

    //     // 9️⃣ Anexar docs + contatos conforme origem
    //     $pessoas = $pessoas->map(function ($pessoa) use ($documentosNovas, $documentosAntigas, $contatosNovas, $contatosAntigas) {
    //         if ($pessoa->origem === 'nova') {
    //             $pessoa->documentos = $documentosNovas->get($pessoa->id, collect());
    //             $pessoa->contatos   = $contatosNovas->get($pessoa->id, collect());
    //         } else {
    //             $pessoa->documentos = $documentosAntigas->get($pessoa->id, collect());
    //             $pessoa->contatos   = $contatosAntigas->get($pessoa->id, collect());
    //         }
    //         return $pessoa;
    //     });

    //     // 🔟 Ordenação final
    //     $pessoas = $pessoas->sortBy(fn($p) => [$p->origem === 'nova' ? 0 : 1, $p->nome])->values();

    //     $end = microtime(true);
    //     $this->executionTime = number_format($end - $start, 4);

    //     return $pessoas;
    // }

    public function searchPeoples(Request $request)
    {
        $start = microtime(true);
        $this->documentosNovos = $this->documentosAntigos = $this->contatosNovos = $this->contatosAntigos = [];

        if ($request->filled('documento_numero')) {
            $this->getDocumentos($request->documento_numero);
        }

        if ($request->filled('contato')) {
            $this->getContatos($request->contato);
        }

        $pessoasNovasIds = array_unique(
            array_merge($this->contatosNovos, $this->documentosNovos)
        );

        $pessoasAntigasIds = array_unique(
            array_merge($this->contatosAntigos, $this->documentosAntigos)
        );

        // 1️⃣ Pessoas novas
        $pessoasNova = Pessoa::query()
            ->select('pessoas.*', DB::raw("'nova' as origem"))
            ->when($request->filled('nome'), fn($q) => $q->where('pessoas.nome', 'like', "%{$request->nome}%"))
            ->when($request->filled('apelido'), fn($q) => $q->where('pessoas.alcunha', 'like', "%{$request->apelido}%"))
            ->whereIn('id', $pessoasNovasIds)
            ->get()
            ->map(fn($p) => tap($p, fn($pessoa) => $pessoa->id_formatado = $pessoa->id . 'N'));

        $nomesNovas = $pessoasNova->pluck('nome')->toArray();

        $pessoasAntiga = PessoaAntiga::query()
            ->from('siapen.tbpessoa as tbpessoa')
            ->select('tbpessoa.*', DB::raw("'antiga' as origem"))
            ->when(!empty($nomesNovas), fn($q) => $q->whereNotIn('tbpessoa.nome', $nomesNovas))
            ->when(
                $request->filled('nome'),
                fn($q) =>
                $q->where('tbpessoa.nome', 'like', "%{$request->nome}%")
            )
            // 🔍 Apenas se a requisição tiver 'apelido', faz o join com interno
            ->when($request->filled('apelido'), function ($q) use ($request) {
                $q->join('siapen.interno as interno', 'interno.idpessoa', '=', 'tbpessoa.id')
                    ->addSelect('interno.alcunha')
                    ->where('interno.alcunha', 'like', "%{$request->apelido}%");
            })
            ->whereIn('id', $pessoasAntigasIds)
            ->get()
            ->map(fn($p) => tap($p, fn($pessoa) => $pessoa->id_formatado = $pessoa->id . 'A'));


        // 3️⃣ Junta pessoas
        $pessoas = $pessoasNova->concat($pessoasAntiga)->values();
        $documentosNovas = $this->pessoasDocumentosNovos();
        $documentosAntigas = $this->pessoaDocumentosAntigos();
        $contatosNovas = $this->pessoaContatosNovos();
        $contatosAntigas = $this->pessoaContatosAntigos();

        // 9️⃣ Anexar docs + contatos conforme origem
        $pessoas = $pessoas->map(function ($pessoa) use ($documentosNovas, $documentosAntigas, $contatosNovas, $contatosAntigas) {
            if ($pessoa->origem === 'nova') {
                $pessoa->documentos = $documentosNovas->get($pessoa->id, collect());
                $pessoa->contatos   = $contatosNovas->get($pessoa->id, collect());
            } else {
                $pessoa->documentos = $documentosAntigas->get($pessoa->id, collect());
                $pessoa->contatos   = $contatosAntigas->get($pessoa->id, collect());
            }
            return $pessoa;
        });

        // 🔟 Ordenação final
        $pessoas = $pessoas->sortBy(fn($p) => [$p->origem === 'nova' ? 0 : 1, $p->nome])->values();

        $end = microtime(true);
        $this->executionTime = number_format($end - $start, 4);

        return $pessoas;
    }


    private function pessoasDocumentosNovos()
    {
        $documentosNovas = DB::connection('siapenweb_dp')
            ->table('pessoa_documentos')
            ->select('pessoa_id', 'documento_tipo_id', 'documento_numero')
            ->whereIn('pessoa_id', $this->documentosNovos)
            ->whereNotNull('documento_numero')
            ->get()
            ->groupBy('pessoa_id');

        return $documentosNovas;
    }

    private function pessoaDocumentosAntigos()
    {
        $documentosAntigas = DB::connection('siapen')
            ->table('pessoa_documento')
            ->select('idpessoa', 'iddocumento as documento_tipo_id', 'numero_documento as documento_numero', 'orgao_expedidor', 'data_expedicao', 'uf_documento', 'pais_documento')
            ->whereIn('idpessoa', $this->documentosAntigos)
            ->whereNotNull('numero_documento')
            ->get()
            ->groupBy('idpessoa');

        return $documentosAntigas;
    }

    private function pessoaContatosNovos()
    {
        // 7️⃣ Contatos novas
        $contatosNovas = DB::connection('siapenweb_dp')
            ->table('pessoa_contatos')
            ->select('pessoa_id', 'contato', 'nome')
            ->whereIn('pessoa_id', $this->contatosNovos)
            ->get()
            ->groupBy('pessoa_id');

        return $contatosNovas;
    }

    private function pessoaContatosAntigos()
    {
        // 8️⃣ Contatos antigas
        $contatosAntigas = DB::connection('siapen')
            ->table('pessoa_contato')
            ->select('idpessoa as pessoa_id', 'idtipo_contato as contato_tipo_id', 'contato')
            ->whereIn('idpessoa', $this->contatosAntigos)
            ->get()
            ->groupBy('pessoa_id');

        return $contatosAntigas;
    }


    public function getContatos($contato)
    {
        $this->contatosNovos = DB::connection('siapenweb_dp')
            ->table('pessoa_contatos')
            ->where('contato', 'like', "%" . $contato . "%")
            ->pluck('pessoa_id')
            ->toArray();

        $this->contatosAntigos = DB::connection('siapen')
            ->table('pessoa_contato')
            ->where('contato', 'like', "%" . $contato . "%")
            ->pluck('idpessoa')
            ->toArray();

        $pessoasIds = Pessoa::whereIn('idpessoa', $this->contatosAntigos)->pluck('id', 'idpessoa')->toArray();

        foreach ($pessoasIds as $idpessoaAntigo => $idNovo) {
            // Se o id novo ainda não estiver nos documentos novos
            if (!in_array($idNovo, $this->documentosNovos)) {
                // Adiciona o id novo na lista de documentos novos
                $this->documentosNovos[] = $idNovo;
            }

            // Remove o idpessoa antigo da lista de documentos antigos
            $this->documentosAntigos = array_values(
                array_diff($this->documentosAntigos, [$idpessoaAntigo])
            );
        }
    }

    public function getDocumentos($documento_numero)
    {
        $this->documentosNovos = DB::connection('siapenweb_dp')
            ->table('pessoa_documentos')
            ->where('documento_numero', 'like', "%" . $documento_numero . "%")
            ->whereNotNull('documento_numero')
            ->pluck('pessoa_id')
            ->toArray();

        $this->documentosAntigos = DB::connection('siapen')
            ->table('pessoa_documento')
            ->where('numero_documento', 'like', "%" . $documento_numero . "%")
            ->whereNotNull('numero_documento')
            ->pluck('idpessoa')
            ->toArray();

        $pessoasIds = Pessoa::whereIn('idpessoa', $this->documentosAntigos)->pluck('id', 'idpessoa')->toArray();

        foreach ($pessoasIds as $idpessoaAntigo => $idNovo) {
            // Se o id novo ainda não estiver nos documentos novos
            if (!in_array($idNovo, $this->documentosNovos)) {
                // Adiciona o id novo na lista de documentos novos
                $this->documentosNovos[] = $idNovo;
            }

            // Remove o idpessoa antigo da lista de documentos antigos
            $this->documentosAntigos = array_values(
                array_diff($this->documentosAntigos, [$idpessoaAntigo])
            );
        }
    }

    public function newPerson(Request $request) {}
}
