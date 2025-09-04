<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\Adm\Acl;
use App\Models\Adm\ProfileRoute;
use App\Models\Adm\Route;
use App\Models\Profile;
use App\Traits\PageHeaderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileRouteController extends Controller
{
    use PageHeaderTrait;

    public function __construct()
    {
        $this->initPageHeader();

        $this->breadcrumbs[] = ['label' => 'Admin', 'icon' => 'ti ti-settings'];
        $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-id'];

        switch (request()->route()->getActionMethod()) {
            case 'edit':
                $this->titulo = 'Alterar Rotas Permitidas';
                $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-key'];
                break;
            case 'create':
                $this->titulo = 'Novo Rotas Permitidas';
                $this->breadcrumbs[] = ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-key'];
                break;

            default:
                $this->titulo = 'Rotas Permitidas';
                break;
        }

        // acrescentando botões
        $this->buttons[] = ['icon' => 'ti ti-plus', 'link' => route('profile.create'), 'bg' => 'bg-gray-600', 'text' => 'text-white', 'hover' => 'bg-gray-900', 'title' => 'Novo registro'];
    }
    public function index()
    {
        $profile = Profile::find(request()->profile_id);
        $acls = Acl::all();
        $routes = Route::all();
        if (Auth::user()->profile_id == 1) {
            // pega todas as rotas
            $profileRoute = ProfileRoute::orderBy('route_id')->get();
        } else {
            $profileRoute = ProfileRoute::where('profile_id', Auth::user()->profile_id)->orderBy('route_id')->get();
        }

        return view('profileRoute.index')
            ->with("routes", $routes)
            ->with("route", route('profileRoute.store'))
            ->with("acls", $acls)
            ->with("profile", $profile)
            ->with('titulo', $this->titulo)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('otherButtons', $this->buttons)
            ->with('profileRoutes', $profileRoute);
    }

    public function store(Request $request)
    {
        // Validação do profile
        $validated = request()->validate([
            'profile_id' => 'required',
        ]);

        if (is_null($request->route)) {
            $existsProfileRoutes = ProfileRoute::where('profile_id', $request->profile_id)->get();

            if ($existsProfileRoutes->isNotEmpty()) {
                ProfileRoute::where('profile_id', $request->profile_id)->forceDelete();
                $alert = Alert::toast('Rotas excluídas com sucesso!', 'success');
            } else {
                Alert::toast('Nenhuma ação foi executada.', 'info');
            }
        } else {
            ProfileRoute::whereNotIn('route_id', $request->route)->where('profile_id', $request->profile_id)->forceDelete();
            foreach ($request->route as $value) {
                if (!ProfileRoute::where('profile_id', $request->profile_id)->where('route_id', $value)->first()) {
                    if (ProfileRoute::create(['profile_id' => $request->profile_id, 'route_id' => $value])) {
                        $alert = Alert::toast('Rotas inseridas com sucesso!', 'success');
                    }
                }
            }
        }

        return redirect()->route("profile.index");
    }
}
