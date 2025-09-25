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
        $custodiados = $this->search(request());
        $parametros = request()->all();

        return view('search.index', compact('situacaoAtual', 'regimes', 'custodiados', 'parametros'));
    }
}
