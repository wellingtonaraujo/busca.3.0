<?php

namespace App\Http\Controllers\Pessoa;

use App\Http\Controllers\Controller;
use App\Models\Adm\Sexo;
use App\Models\Pessoa\Entidade;
use App\Models\Pessoa\Pessoa;
use App\Models\User;
use App\Rules\ValidarCampos;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\Request;

class PessoaController extends Controller
{

    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];

        switch (request()->route()->getActionMethod()) {
            case 'edit':
                $this->titulo = 'Alterar Pessoa';
                $this->breadcrumbs[] = ['label' => 'Pessoas', 'link' => route('pessoa.index'), 'icon' => 'ti ti-key'];
                break;
            case 'create':
                $this->titulo = 'Nova Pessoa';
                $this->breadcrumbs[] = ['label' => 'Pessoas', 'link' => route('pessoa.index'), 'icon' => 'ti ti-key'];
                break;

            default:
                $this->titulo = 'Pessoas';
                break;
        }

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('pessoa.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }

    public function index()
    {
        return view('pessoa.index')
            ->with('route', "pessoa")
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('pessoas', Pessoa::orderBy('id')->get());
    }

    public function create()
    {
        $sexoOptions = Sexo::orderBy('id')->pluck('descricao', 'id');
        $entidadeOptions = Entidade::orderBy('id')->pluck('nome', 'id');
        return view('pessoa.create')
            ->with("entidadeOptions", $entidadeOptions)
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with("sexoOptions", $sexoOptions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'        => ['required'],
            'cpf'         => ['required', new ValidarCampos('cpf')],
            'entidade_id' => ['required'],
            'matricula'   => ['required'],
            'nascimento'  => ['required'],
            'sexo_id'     => ['required'],
            'cep'         => ['required', new ValidarCampos('cep')],
            'logradouro'  => ['required'],
            'numero'      => ['required'],
            'complemento' => ['nullable', 'string'],
            'bairro'      => ['required'],
            'cidade'      => ['required'],
            'uf'          => ['required'],
            'email'       => ['required', new ValidarCampos('cpf')],
            'celular'     => ['required', new ValidarCampos('celular')],
            'foto_perfil' => ['nullable', 'image', 'max:2048'], // pode ser nulo se o usuário não enviar
        ]);

        if ($request->hasFile('foto_perfil') && $request->file('foto_perfil')->isValid()) {
            $file = $request->file('foto_perfil');
            // Lê o conteúdo binário da imagem
            $validated['foto_perfil'] = file_get_contents($file->getRealPath());
        } else {
            // Remove do array se não houver upload para não sobrescrever
            unset($validated['foto_perfil']);
        }

        // criar a pessoa
        $pessoaCreated = Pessoa::create($validated);

        return redirect()->route('pessoa.index')->with('success', 'Pessoa criada com sucesso!');
    }

    public function edit($id)
    {
        $sexoOptions = Sexo::orderBy('id')->pluck('descricao', 'id');
        $entidadeOptions = Entidade::orderBy('id')->pluck('nome', 'id');
        return view('pessoa.create')
            ->with('pessoa', Pessoa::find($id))
            ->with("entidadeOptions", $entidadeOptions)
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with("sexoOptions", $sexoOptions);
    }

    public function update(Request $request, Pessoa $pessoa)
    {
        $validated = $request->validate([
            'nome'        => ['required'],
            'cpf'         => ['required'],
            'entidade_id' => ['required'],
            'matricula'   => ['required'],
            'nascimento'  => ['required'],
            'sexo_id'     => ['required'],
            'cep'         => ['required'],
            'logradouro'  => ['required'],
            'numero'      => ['required'],
            'complemento' => ['nullable', 'string'],
            'bairro'      => ['required'],
            'cidade'      => ['required'],
            'uf'          => ['required'],
            'email'       => ['required', 'email'],
            'celular'     => ['required'],
            // 'foto_perfil' => ['image', 'max:2048'], // pode ser nulo se o usuário não enviar
            'foto_perfil' => 'file|image|max:2040', // até 5MB
        ]);



        if ($request->hasFile('foto_perfil') && $request->file('foto_perfil')->isValid()) {
            $file = $request->file('foto_perfil');
            // lê o conteúdo binário real
            $validated['foto_perfil'] = file_get_contents($file->getPathname());
        }

        // dd([
        //     'tamanho' => isset($validated['foto_perfil']) ? strlen($validated['foto_perfil']) : null,
        //     'primeiros_bytes' => isset($validated['foto_perfil']) ? substr($validated['foto_perfil'], 0, 20) : null
        // ]);

        $pessoa->update($validated);

        return redirect()->route('pessoa.index')->with('success', 'Pessoa atualizada com sucesso!');
    }

    public function getCpf($id)
    {
        $pessoa = Pessoa::find($id);

        if (!$pessoa) {
            return response()->json(['error' => 'Pessoa não encontrada'], 404);
        }

        return response()->json([
            'cpf' => $pessoa->cpf,
        ]);
    }

    public function usuario(Pessoa $pessoa)
    {
        $usuario = User::where('pessoa_id', $pessoa->id)->first();

        if (is_null($usuario)) {
            return redirect()->route('user.create', ['pessoa' => $pessoa]);
        } else {
            return redirect()->route('user.edit', $usuario->id);
        }
    }
}
