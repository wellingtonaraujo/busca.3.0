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
        $search = DB::connection('siapenweb_dp')->table('custodiados')
            ->join('pessoas', 'custodiados.pessoa_id', '=', 'pessoas.id') // ajusta conforme suas chaves
            ->when($request->nome, function ($query, $nome) {
                $query->where('nome', 'like', "%{$nome}%");
            })
            ->when($request->alcunha, function ($query, $alcunha) {
                $query->where('alcunha', 'like', "%{$alcunha}%");
            })
            ->when($request->regime, function ($query, $regime) {
                $query->where('regime_id', $regime);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('custodiado_situacao_atual_id', $status);
            })
            ->whereNotNull('nome') // garante que nome não seja nulo
            ->when($request->order_by, function ($query, $order_by) {
                $query->orderBy($order_by);
            }, function ($query) {
                $query->orderBy('nome'); // fallback quando $request->order_by é null
            })
            ->get();

        return $search;
    }
}
