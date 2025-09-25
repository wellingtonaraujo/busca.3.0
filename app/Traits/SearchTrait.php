<?php

namespace App\Traits;

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
        dump($request->all());

        if (!empty(array_filter($request->all()))) {
            $search = DB::connection('siapenweb_dp')->table('custodiados')
                ->join('pessoas', 'custodiados.pessoa_id', '=', 'pessoas.id') // ajusta conforme suas chaves
                ->join('pessoa_documentos as pd', 'pd.pessoa_id', 'pessoas.id')
                ->join('regimes', 'regimes.id', 'custodiados.regime_id')
                ->join('custodiado_situacao_atuals as situacao', 'situacao.id', 'custodiados.custodiado_situacao_atual_id')
                ->when($request->nome, function ($query, $nome) {
                    $query->where('nome', 'like', "%{$nome}%");
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
                ->when($request->cpf, function ($query, $cpf) {
                    $query->where('pd.documento_tipo_id', 2)
                        ->where('pd.documento_numero', $cpf);
                })
                ->when($request->rg, function ($query, $rg) {
                    $query->where('pd.documento_tipo_id', 1)
                        ->where('pd.documento_numero', $rg);
                })
                ->whereNotNull('nome') // garante que nome não seja nulo
                ->when($request->order_by, function ($query, $order_by) {
                    $query->orderBy($order_by);
                }, function ($query) {
                    $query->orderBy('nome'); // fallback quando $request->order_by é null
                })
                ->select(
                    'custodiados.id',
                    'pessoas.nome',
                    'pessoas.alcunha',
                    'regimes.descricao as regime',
                    'situacao.descricao as status',
                )
                ->get();

            return $search;
        }

        return null;
    }

    public function parametros(Request $request){
        if (!empty(array_filter($request->all()))) {
            return $request->all();
        }

        return null;
    }
}
