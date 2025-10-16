<?php

namespace App\Traits;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\CustodiadoAntigo;
use App\Models\Custodiado\Pessoa;
use App\Models\Custodiado\PessoaAntiga;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SearchTrait
{
    protected $tempoExecucao = 0;

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

        $this->tempoExecucao = round($end - $start, 4) . 's';
        return $todasPessoas;
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

    /**
     * Cria aliases "sem Antigo" no $custodiado e dentro de $custodiado->pessoa
     * Ex.: vinculadoAntigo -> vinculado; documentosAntigos -> documentos; etc.
     */
    protected function aliasLegacyOnCustodiado(CustodiadoAntigo $custodiado): void
    {
        // ---------- ALIASES DIRETOS DO CUSTODIADO ----------
        // Mapeamentos explícitos (seguros)
        $mapSelf = [
            'vinculadoAntigo' => 'vinculado',
            // outras relações locais com "Antigo" você pode adicionar aqui…
        ];

        foreach ($mapSelf as $from => $to) {
            if (method_exists($custodiado, $from)) {
                $rel = $custodiado->{$from}(); // Relation
                if ($rel instanceof Relation) {
                    $custodiado->setRelation($to, $rel->getResults());
                } else {
                    // caso não seja Relation, cai como atributo “normal”
                    $custodiado->setAttribute($to, $custodiado->{$from});
                }
            }
        }

        // ---------- ALIASES DENTRO DE PESSOA ----------
        if ($custodiado->relationLoaded('pessoa') || $custodiado->pessoa) {
            $pessoa = $custodiado->pessoa;

            // Mapeamentos explícitos (seguros) em PessoaAntiga
            $mapPessoa = [
                'custodiadoAntigo'       => 'custodiado',
                'documentosAntigos'      => 'documentos',
                'contatosAntigos'        => 'contatos',
                // mantive os nomes autoexplicativos para diferenciar as duas pontas
                'vinculadosComoVisitante' => 'vinculados_visitante',
                'vinculadosComoInterno'  => 'vinculados_interno',
                'internoVisitante'       => 'interno_visitante',
            ];

            foreach ($mapPessoa as $from => $to) {
                if (method_exists($pessoa, $from)) {
                    $rel = $pessoa->{$from}(); // Relation
                    if ($rel instanceof Relation) {
                        $pessoa->setRelation($to, $rel->getResults());
                    } else {
                        $pessoa->setAttribute($to, $pessoa->{$from});
                    }
                }
            }

            // O accessor getVinculadoAntigoAttribute() já faz o merge dos dois
            // conjuntos de vínculos; alias final para 'vinculados'
            $pessoa->setAttribute('vinculados', $pessoa->vinculado_antigo);

            // Atalhos úteis direto no $custodiado (se quiser acessar sem “->pessoa”)
            $custodiado->setRelation('pessoa_documentos', $pessoa->getRelation('documentos') ?? collect());
            $custodiado->setRelation('pessoa_contatos',   $pessoa->getRelation('contatos')   ?? collect());
            $custodiado->setRelation('vinculados',        $pessoa->getAttribute('vinculados') ?? collect());
        }

        // ---------- PASSO OPCIONAL: ALIAS AUTOMÁTICO POR REFLEXÃO ----------
        // Qualquer método *de relação* que termine com "Antigo" vira um alias
        // sem o sufixo (ex.: "fooAntigo" -> "foo").
        $this->aliasByReflection($custodiado, CustodiadoAntigo::class);
        if ($custodiado->pessoa) {
            $this->aliasByReflection($custodiado->pessoa, \App\Models\Custodiado\PessoaAntiga::class);
        }
    }

    /**
     * Cria aliases automaticamente para métodos de relação que terminem com "Antigo".
     * Ex.: metodoAntigo() => alias "metodo"
     */
    protected function aliasByReflection(object $model, string $classFqcn): void
    {
        try {
            $rc = new \ReflectionClass($classFqcn);
            foreach ($rc->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
                $name = $m->getName();

                // ignora herdados do Model/traits e só pega os deste FQCN
                if ($m->getDeclaringClass()->getName() !== $classFqcn) {
                    continue;
                }

                if (!str_ends_with($name, 'Antigo')) {
                    continue;
                }

                // tenta invocar e ver se retorna Relation
                $result = null;
                try {
                    $result = $model->{$name}();
                } catch (\Throwable $e) {
                    continue; // se não invocar como relação, pula
                }

                if ($result instanceof Relation) {
                    $alias = substr($name, 0, -strlen('Antigo'));
                    // se já existir um alias explícito, respeitamos (não sobrescreve)
                    if (!$model->relationLoaded($alias)) {
                        $model->setRelation($alias, $result->getResults());
                    }
                }
            }
        } catch (\Throwable $e) {
            // silencioso por segurança; se preferir, logue aqui
        }
    }
}
