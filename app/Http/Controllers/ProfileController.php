<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pessoa\Entidade;
use App\Models\Profile;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];

        switch (request()->route()->getActionMethod()) {
            case 'edit':
                $this->titulo = 'Alterar Perfil de Acesso';
                $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-key'];
                break;
            case 'create':
                $this->titulo = 'Novo Perfil de Acesso';
                $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-key'];
                break;

            default:
                $this->titulo = 'Perfil de Acesso';
                break;
        }

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-key', 'link' => route('acl.index'),'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Controle de Acesso.'];
        $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('profile.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }

    public function index()
    {
        return view('profile.index')
            ->with('route', 'profile')
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('profiles', Profile::get());
    }

    public function create()
    {
        return view('profile.create')
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('entidades', Entidade::orderBy('id')->pluck('nome', 'id'));
    }

    public function store(Request $request)
    {
        //validação dos dados
        $validated = $request->validate(
            [
                'name'           => ['required', 'string', 'unique:profiles,name'],
                'descricao'      => ['required', 'string'],
                'entidade_id'    => ['required', 'integer'],
                'expiracao_adm'  => ['required', 'integer'],
                'expiracao_user' => ['required', 'integer'],
            ]
        );

        try {
            DB::beginTransaction();

            $profile = Profile::create($validated);

            DB::commit();

            Alert::success('Salvar', 'Perfil de usuário criado com sucesso!');
            return redirect()->route('profile.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th); // Para fins de debug, se necessário
            Alert::warning('Salvar', 'Algo deu errado. Tente novamente! ' . $th->getCode() . " => " . $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $profile = Profile::find($id);
        return view('profile.create')
            ->with('entidades', Entidade::orderBy('id')->pluck('nome', 'id'))
            ->with('profile', $profile);
    }

    public function update(Request $request, Profile $profile)
    {

        //validação dos dados
        $validated = $request->validate(
            [
                'name' => ['required', 'string'],
                'descricao' => ['required', 'string'],
            ]
        );

        try {
            DB::beginTransaction();

            $profile->update([
                'name' => $validated['name'],
                'descricao' => $validated['descricao'],
            ]);

            DB::commit();

            Alert::success('Atualizar', 'Perfil de usuário atualizado com sucesso!');
            return redirect()->route('profile.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th); // Para fins de debug, se necessário
            Alert::warning('Atualizar', 'Algo deu errado. Tente novamente! ' . $th->getCode() . " => " . $th->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Profile $profile)
    {
        try {
            DB::beginTransaction();

            // Soft delete (ou hard delete se não usar SoftDeletes)
            $profile->delete();

            DB::commit();

            Alert::success('Remover', 'Perfil removido com sucesso!');
            return redirect()->route('profile.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);
            Alert::warning('Remover', 'Erro ao remover o perfil.');
            return back();
        }
    }
}
