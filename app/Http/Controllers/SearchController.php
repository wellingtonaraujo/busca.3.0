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
        $resultado = $this->search(request());
        $pessoas = $resultado['data'];
        $tempo_execucao = $resultado['tempo_execucao'];
        $parametros = request()->all();

        return view('search.index', compact('pessoas', 'parametros', 'tempo_execucao'));
    }
}
