<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Adm\Acl;
use App\Models\Adm\Route;
use App\Models\Adm\RouteMetodo;
use App\Models\Audit\UserActionLog;
use App\Models\Profile;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AclController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];
        $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-id'];

        switch (request()->route()->getActionMethod()) {
            case 'edit':
                $this->titulo = 'Alterar Controle de Acesso';
                $this->breadcrumbs[] = ['label' => 'Acl', 'link' => route('acl.index'), 'icon' => 'ti ti-key'];
                break;
            case 'create':
                $this->titulo = 'Novo Controle de Acesso';
                $this->breadcrumbs[] = ['label' => 'Acl', 'link' => route('acl.index'), 'icon' => 'ti ti-key'];
                break;

            default:
                $this->titulo = 'Controle de Acesso';
                break;
        }

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('acl.create'),'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }

    public function index()
    {
        // dd($this->breadcrumbs, $this->buttons);
        return view('acl.index')
            ->with('route', 'acl')
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('acls', Acl::get());
    }

    public function create()
    {
        return view('acl.create')
            ->with('route', 'acl')
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons);
    }

    public function store(Request $request)
    {
        //validação dos dados
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'unique:acls,name'],
                'descricao' => ['required', 'string'],
            ]
        );

        DB::beginTransaction();
        try {
            $acl = Acl::create([
                'name' => $validated['name'],
                'descricao' => $validated['descricao'],
            ]);

            if ($acl->wasRecentlyCreated) {
                $metodos = RouteMetodo::all();
                foreach ($metodos as $metodo) {
                    $cresatedRoute = Route::create(['name' => "$acl->name.$metodo->name"]);
                }
            }

            DB::commit();

            Alert::success('Salvar', 'ACL criada com sucesso!');
            return redirect()->route('acl.index', ['profile_id' => $request->profile_id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th); // Para fins de debug, se necessário
            switch ($th->getCode()) {
                case 23000:
                    Alert::warning('Salver', 'Já existe um registro com este nome em nossa base de dados');
                    break;

                default:
                    Alert::warning('Salvar', 'Algo deu errado. Tente novamente! ' . $th->getCode() . " => " . $th->getMessage());
                    break;
            }
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $acl = Acl::find($id);
        return view('acl.create')
            ->with('route', 'acl')
            ->with("acl", $acl)
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons);
    }

    public function update(Request $request, Acl $acl)
    {
        try {
            $oldAcl = $acl;
            $routes = Route::where('name', "like", "$oldAcl->name%")->get();

            //validação dos dados
            $validated = $request->validate(
                [
                    'name' => ['required', 'string'],
                    'descricao' => ['required', 'string'],
                ]
            );

            DB::beginTransaction();

            $acl->update([
                'name' => $validated['name'],
                'descricao' => $validated['descricao'],
            ]);

            if ($routes->count() > 0) {
                foreach ($routes as $route) {
                    $metodo = explode(".", $route->name);
                    $updatedRoute = $route->update(['name' => "$acl->name.$metodo[1]"]);
                }
            } else {
                $metodos = RouteMetodo::all();
                foreach ($metodos as $metodo) {
                    $createdRoute = Route::create(['name' => "$acl->name.$metodo->name"]);
                }
            }

            DB::commit();

            Alert::success('Salvar', 'ACL atualizada com sucesso!');
            return redirect()->route('acl.index', ['profile_id' => $request->profile_id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th); // Para fins de debug, se necessário
            Alert::warning('Salvar', 'Algo deu errado. Tente novamente! ' . $th->getCode() . " => " . $th->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Acl $acl)
    {
        try {
            DB::beginTransaction();

            $routes = Route::where('name', 'like', "$acl->name.%")->forceDelete();

            // Soft delete (ou hard delete se não usar SoftDeletes)
            $acl->forceDelete();

            DB::commit();

            Alert::success('Remover', 'Acl removida com sucesso!');
            return redirect()->route('acl.index', ['profile_id' => request()->profile_id]);
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);
            Alert::warning('Remover', 'Erro ao remover a acl.');
            return back();
        }
    }
}
