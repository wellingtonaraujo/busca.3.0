<?php

namespace App\Http\Controllers\Pessoa;

use App\Http\Controllers\Controller;
use App\Models\Pessoa\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index()
    {
        return view('pessoa.index')
            ->with('route', 'acl')
            ->with('pessoas', Pessoa::orderBy('id')->paginate(10));
    }
}
