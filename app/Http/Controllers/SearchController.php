<?php

namespace App\Http\Controllers;

use App\Models\Custodiado\CustodiadoAntigo;
use App\Models\Custodiado\PessoaAntiga;
use App\Models\Custodiado\Regime;
use App\Models\Custodiado\Vinculado;
use App\Traits\SearchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    use SearchTrait;
    //meto do index
    public function index()
    {
        $pessoas = $this->search(request());
        $tempo_execucao = $this->tempoExecucao;
        $parametros = request()->all();

        return view('search.index', compact('pessoas', 'parametros', 'tempo_execucao'));
    }

    public function consultaPrisional(Request $request){
        dd($request->all());
    }
}
