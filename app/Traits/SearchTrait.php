<?php

namespace App\Traits;

use App\Models\Custodiado\Custodiado;
use App\Models\Custodiado\Pessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait SearchTrait
{
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

        if ($request->has('dacumento_numero')) {
            $request->merge(['documento_numero' => $request->dacumento_numero]);
        }

        $query = Custodiado::query()->with(['pessoa', 'pessoa.documentos', 'pessoa.contatos']);

        // Filtrar pelo nome
        $query->when($request->nome, function ($q, $nome) {
            $q->whereHas('pessoa', fn($q2) => $q2->where('nome', 'like', "%{$nome}%"));
        });

        // Filtrar pelo documento (tipo e número)
        $query->when($request->documento_tipo_id, function ($q, $tipo) use ($request) {
            $q->whereHas('pessoa.documentos', function ($q2) use ($tipo, $request) {
                $q2->where('documento_tipo_id', $tipo);
                if ($request->documento_numero) {
                    $q2->where('documento_numero', $request->documento_numero);
                }
            });
        });

        // Ordenação padrão por nome
        $query->orderBy($request->order_by ?? 'id');

        $custodiados = $query->get();

        return $custodiados;
    }

    public function getCustodiados(Request $request)
    {
        $pessoa_ids = !is_null($request->nome)
            ? Pessoa::select('id')->where('nome', 'like', "%{$request->nome}%")->whereNotNull('nome')->get()->toArray()
            : null;

        $search = Custodiado::when($pessoa_ids, function ($query, $pessoa_ids) {
            $query->whereIn('pessoa_id', $pessoa_ids);
        })
            ->when($request->apelido, function ($query, $apelido) {
                $query->where('alcunha', 'like', "%{$apelido}%");
            })
            ->when($request->regime, function ($query, $regime) {
                $query->where('regime_id', $regime);
            })
            ->when($request->custodiado_situacao_atual_id, function ($query, $custodiado_situacao_atual_id) {
                $query->where('custodiado_situacao_atual_id', $custodiado_situacao_atual_id);
            })
            // ->when($request->cpf, function ($query, $cpf) {
            //     $query->where('pd.documento_tipo_id', 2)
            //         ->where('pd.documento_numero', $cpf);
            // })
            // ->when($request->rg, function ($query, $rg) {
            //     $query->where('pd.documento_tipo_id', 1)
            //         ->where('pd.documento_numero', $rg);
            // })

            ->when($request->order_by, function ($query, $order_by) {
                $query->orderBy($order_by);
            }, function ($query) {
                $query->orderBy('id'); // fallback quando $request->order_by é null
            })
            // ->select(
            //     'custodiados.id',
            //     'pessoas.nome',
            //     'pessoas.alcunha',
            //     'regimes.descricao as regime',
            //     'situacao.descricao as status',
            // )
            ->get();

        return $search;
    }
}
