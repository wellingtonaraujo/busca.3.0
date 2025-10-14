<?php

namespace App\Traits;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\Pessoa;
use App\Models\Custodiado\PessoaAntiga;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait SearchTrait
{
    public function search(Request $request)
    {
        // retorna null se a $request->all() retiver vazia;
        if ($this->requestEmpty($request)) {
            return null;
        }

        return $this->searchPeoples($request);
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

    public function searchPeoples(Request $request)
    {
        $start = microtime(true);

        $pessoasNovas = $this->buscarPessoa($request);

        $pessoasAntigas = $this->buscarPessoaAntiga($request, $pessoasNovas->pluck('nome')->toArray());

        // 🔹 aliases já são feitos em buscarPessoaAntiga()
        $pessoasAntigas->transform(function ($p) {
            $p->custodiado = $p->custodiadoAntigo;
            $p->documentos = $p->documentosAntigos;
            $p->contatos = $p->contatosAntigos;
            return $p;
        });

        $todasPessoas = $pessoasNovas->concat($pessoasAntigas)
            ->unique('nome')
            ->values();

        $end = microtime(true);

        return [
            'data' => $todasPessoas,
            'tempo_execucao' => round($end - $start, 4) . 's'
        ];
    }


    /**
     * Gera chave de dedup por nome normalizado + nascimento (YYYY-MM-DD).
     */
    private function nomeKey(?string $nome, $nascimento): ?string
    {
        if (!$nome && !$nascimento) return null;

        $nome = $nome ? $this->normalize($nome) : '';
        // nascimento pode ser Carbon|string|null; normaliza
        try {
            $nasc = $nascimento ? \Illuminate\Support\Carbon::parse($nascimento)->format('Y-m-d') : '';
        } catch (\Throwable $e) {
            $nasc = (string) $nascimento;
        }

        $key = trim($nome . '|' . $nasc);
        return $key !== '|' ? $key : null;
    }

    /**
     * Normaliza nome: remove acentos, múltiplos espaços e baixa caixa.
     */
    private function normalize(string $str): string
    {
        $str = \Illuminate\Support\Str::of($str)
            ->lower()
            ->squish()
            ->ascii()
            ->value();

        return $str;
    }



    private function buscarPessoa(Request $request)
    {
        return Pessoa::query()
            ->select('pessoas.*', DB::raw("'nova' as origem"))
            ->with([
                'custodiado.regime',
                'custodiado.situacaoAtual',
                'vinculado',
                'documentos' => fn($q) => $q->select('id', 'pessoa_id', 'documento_tipo_id', 'documento_numero'),
                'contatos.vinculadoTipo',
            ])
            ->when($request->filled('nome'), fn($q) => $q->where('pessoas.nome', 'like', "%{$request->nome}%"))
            ->when($request->filled('apelido'), fn($q) => $q->where('pessoas.alcunha', 'like', "%{$request->apelido}%"))
            ->when(
                $request->filled('contato'),
                fn($q) =>
                $q->whereHas('contatos', fn($q2) => $q2->where('contato', 'like', "%{$request->contato}%"))
            )
            ->when(
                $request->filled('documento_numero'),
                fn($q) =>
                $q->whereHas('documentos', fn($q2) => $q2->where('documento_numero', 'like', "%{$request->documento_numero}%"))
            )
            ->where(fn($query) => $query->whereHas('custodiado')->orWhereHas('vinculado'))
            ->get()
            ->map(fn($p) => tap($p, fn($pessoa) => $pessoa->id_formatado = "{$pessoa->id} (ID)"));
    }

    private function buscarPessoaAntiga(Request $request, array $nomesNovas)
    {
        $pessoasAntigas = PessoaAntiga::query()
            ->from('siapen.tbpessoa as tbpessoa')
            ->select('tbpessoa.*', DB::raw("'antiga' as origem"))
            ->with([
                'custodiadoAntigo.regime',
                'custodiadoAntigo.situacaoAtual',
                'vinculadosComoVisitante',
                'vinculadosComoInterno',
                'documentosAntigos',
                'contatosAntigos.vinculadoTipo',
            ])

            // evita nomes já encontrados nas "novas"
            ->when(
                !empty($nomesNovas),
                fn($q) =>
                $q->whereNotIn('tbpessoa.nome', $nomesNovas)
            )

            // filtro por nome
            ->when(
                $request->filled('nome'),
                fn($q) =>
                $q->where('tbpessoa.nome', 'like', "%{$request->nome}%")
            )

            // filtro por apelido via relação (sem join manual)
            ->when(
                $request->filled('apelido'),
                fn($q) =>
                $q->whereHas(
                    'custodiadoAntigo',
                    fn($qq) =>
                    $qq->where('alcunha', 'like', "%{$request->apelido}%")
                )
            )

            // filtros extras (alinhados com "novas")
            ->when(
                $request->filled('contato'),
                fn($q) =>
                $q->whereHas(
                    'contatosAntigos',
                    fn($qq) =>
                    $qq->where('contato', 'like', "%{$request->contato}%")
                )
            )
            ->when(
                $request->filled('documento_numero'),
                fn($q) =>
                $q->whereHas(
                    'documentosAntigos',
                    fn($qq) =>
                    $qq->where('documento_numero', 'like', "%{$request->documento_numero}%")
                )
            )

            // precisa ter custódia antiga OU registro como interno_visitante
            ->where(
                fn($q) =>
                $q->whereHas('custodiadoAntigo')
                    ->orWhereHas('internoVisitante')
            )

            // evita duplicados (se surgir algum join implícito)
            ->distinct()

            // ordenação opcional
            ->orderBy('tbpessoa.nome')

            ->get()

            // uniformização de aliases + id_formatado
            ->map(function ($p) {
                $p->id_formatado = "{$p->id} (CAD)";
                $p->custodiado   = $p->custodiadoAntigo;
                $p->documentos   = $p->documentosAntigos;
                $p->contatos     = $p->contatosAntigos;
                // accessor em snake_case
                $p->vinculado    = $p->vinculado_antigo;
                return $p;
            });

        return $pessoasAntigas;
    }
}
