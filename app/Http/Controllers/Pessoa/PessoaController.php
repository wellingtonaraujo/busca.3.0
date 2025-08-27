<?php

namespace App\Http\Controllers\Pessoa;

use App\Http\Controllers\Controller;
use App\Models\Adm\Sexo;
use App\Models\Pessoa\Entidade;
use App\Models\Pessoa\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index()
    {
        return view('pessoa.index')
            ->with('route', "pessoa")
            ->with('pessoas', Pessoa::orderBy('id')->paginate(10));
    }

    public function create(){
        $sexoOptions = Sexo::orderBy('id')->pluck('descricao', 'id');
        $entidadeOptions = Entidade::orderBy('id')->pluck('nome', 'id');
        return view('pessoa.create')
            ->with("entidadeOptions", $entidadeOptions)
            ->with("sexoOptions", $sexoOptions);
    }
}
