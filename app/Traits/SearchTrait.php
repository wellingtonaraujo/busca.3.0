<?php

namespace App\Traits;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\Pessoa;
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

    private function getPeople(Request $request)
    {
        $start = microtime(true); // inicia cronômetro

        $query = Pessoa::query()->select('pessoas.*');

        // Join condicional para documentos
        if ($request->filled('documento_tipo_id') || $request->filled('documento_numero')) {
            $query->join('pessoa_documentos as pd', 'pessoas.id', '=', 'pd.pessoa_id');

            if ($request->filled('documento_tipo_id')) {
                $query->where('pd.documento_tipo_id', $request->documento_tipo_id);
            }

            if ($request->filled('documento_numero')) {
                $query->where('pd.documento_numero', $request->documento_numero);
            }
        }

        // Join condicional para contatos
        if ($request->filled('contato') || $request->filled('contato_nome')) {
            $query->join('pessoa_contatos as pc', 'pessoas.id', '=', 'pc.pessoa_id');

            if ($request->filled('contato')) {
                $query->where('pc.contato', 'like', "%{$request->contato}%");
            }

            if ($request->filled('contato_nome')) {
                $query->where('pc.nome', 'like', "%{$request->contato_nome}%");
            }
        }

        // Filtro por nome
        if ($request->filled('nome')) {
            $query->where('pessoas.nome', 'like', "%{$request->nome}%");
        }

        // Filtro por apelido
        if ($request->filled('apelido')) {
            $query->where('pessoas.alcunha', 'like', "%{$request->apelido}%");
        }

        // Ordenação (default: nome)
        $orderBy = $request->order_by ?? 'pessoas.nome';
        $query->orderBy($orderBy);

        // Evita duplicados por conta dos joins
        $query->distinct();

        // Execução
        $pessoas = $query->get();

        $end = microtime(true);
        $executionTime = number_format($end - $start, 4);

        $this->executionTime = $executionTime;

        return $pessoas;
    }
}
