<?php

namespace App\Http\Controllers\Pessoa;

use App\Http\Controllers\Controller;
use App\Models\Adm\Sexo;
use App\Models\Pessoa\Entidade;
use App\Models\Pessoa\Pessoa;
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
            ->with('pessoas', Pessoa::orderBy('id')->paginate(10));
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
            'cidade'      => ['required'],
            'uf'          => ['required'],
            'email'       => ['required', 'email'],
            'celular'     => ['required'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
        ]);

        // dd($request->all(), $validated);

        // upload da imagem (se enviada)
        if ($request->hasFile('foto_perfil')) {
            $file = $request->file('foto_perfil');
            if ($file->isValid()) {
                $destination = public_path('uploads/fotos_perfil');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destination, $filename);
                $validated['foto_perfil'] = 'uploads/fotos_perfil/' . $filename;
            }
        }

        // atualiza os dados
        $pessoa->update($validated);

        return redirect()->route('pessoas.index')->with('success', 'Pessoa atualizada com sucesso!');
    }
}
