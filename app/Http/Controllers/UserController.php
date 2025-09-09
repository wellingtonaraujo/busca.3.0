<?php

namespace App\Http\Controllers;

use App\Models\Adm\UserStatus;
use App\Models\Pessoa\Entidade;
use App\Models\Pessoa\Pessoa;
use App\Models\Profile;
use App\Models\User;
use App\Rules\ValidarCampos;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];

        switch (request()->route()->getActionMethod()) {
            case 'edit':
                $this->titulo = 'Alterar Usuário';
                $this->breadcrumbs[] = ['label' => 'Usuários', 'link' => route('user.index'), 'icon' => 'ti ti-key'];
                break;
            case 'create':
                $this->titulo = 'Nova Usuário';
                $this->breadcrumbs[] = ['label' => 'Usuários', 'link' => route('user.index'), 'icon' => 'ti ti-key'];
                break;

            default:
                $this->titulo = 'Usuários';
                break;
        }

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('user.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }
    public function index()
    {
        return view('user.index')
            ->with('route', "user")
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('users', User::orderBy('id')->get());
    }

    public function create()
    {
        Alert::info('Informação', "Esta função está em contrução.");
        return view('user.index')
            ->with('route', "user")
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('users', User::orderBy('id')->get());
    }

    public function edit(User $user)
    {
        $entidadeOptions = Entidade::orderBy('nome')->pluck('nome', 'id');
        $pessoaOptions = Pessoa::orderBy('nome')->pluck('nome', 'id');
        $statusOptions = UserStatus::orderBy('id')->pluck('descricao', 'id');

        return view('user.create')
            ->with('route', "user")
            ->with('entidadeOptions', $entidadeOptions)
            ->with('pessoaOptions', $pessoaOptions)
            ->with('statusOptions', $statusOptions)
            ->with('profileOptions', Profile::orderBy('descricao')->pluck('name', 'id'))
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('user', $user);
    }

    public function update(Request $request, User  $user)
    {
        if(!isset($request->cpf)){
            $pessoa = Pessoa::find($request->pessoa_id);
            $request->merge(['cpf' => $pessoa->cpf]);
        }
        $user->update($request->all());
        dd($request->all(), $user);
    }
}
