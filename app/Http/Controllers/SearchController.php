<?php

namespace App\Http\Controllers;

use App\Traits\SearchTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    use SearchTrait;
    //meto do index
    public function index()
    {
        $situacaoAtual = $this->situacaoAtualOpcoes();
        $regimes = $this->regimeOpcoes();
        $pessoas = $this->search(request());
        $parametros = request()->all();
        $tempoExecucao = $this->executionTime;

        return view('search.index', compact('situacaoAtual', 'regimes', 'pessoas', 'parametros', 'tempoExecucao'));
    }
}
