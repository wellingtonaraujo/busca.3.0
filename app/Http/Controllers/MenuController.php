<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ProfileMenu;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu.index')
            ->with('route', "menu")
            ->with('menus', Menu::get());
    }

    public function create()
    {
        $Profiles = Profile::pluck('name', 'id');

        return view('menu.create')
            ->with('parents', Menu::pluck('name', 'id'))
            ->with('menu', null)
             ->with('parents', Menu::pluck('name', 'id')->toArray())
            ->with('profiles', $Profiles);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'parent_id' => ['nullable', 'exists:menus,id'],
                'order_no' => ['required', 'integer'],
                'name' => ['required', 'string', 'unique:menus,name'],
                'icon' => ['required', 'string'],
                'is_active' => ['required', 'boolean'],
                'profiles' => ['required', 'array'],
                'profiles.*' => [Rule::exists('profiles', 'id')],
            ]);


            DB::beginTransaction();

            $menu = Menu::create([
                'parent_id' => $validated['parent_id'] ?? null,
                'order_no' => $validated['order_no'],
                'name' => $validated['name'],
                'icon' => $validated['icon'],
                'route' => $request->route,
                'is_active' => $validated['is_active'],
            ]);

            // Relaciona os Profile via Eloquent
            $menu->Profile()->sync($validated['profiles']);

            DB::commit();

            Alert::success('Salvar', 'Menu criado com sucesso!');
            return redirect()->route('menu.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e); // Para fins de debug, se necessário
            Alert::warning('Salvar', 'Algo deu errado. Tente novamente! ' . $e->getCode() . " => " . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $menu = Menu::find($id);

        $Profiles = Profile::pluck('name', 'id');
        $profile_selected_ids = ProfileMenu::where('menu_id', $id)->get()->toArray();

        return view('menu.create')
            ->with('menu', $menu)
            ->with('profiles', $Profiles)
            ->with('parents', Menu::pluck('name', 'id')->toArray());
    }

    public function update(Request $request, Menu $menu)
    {
        // 0 => Undefined array key "route"
        try {
            $validated = $request->validate([
                'parent_id' => ['nullable', 'exists:menus,id'],
                'order_no' => ['required', 'integer'],
                'name' => ['required', 'string', Rule::unique('menus', 'name')->ignore($menu->id)],
                'icon' => ['required', 'string'],
                'is_active' => ['required', 'boolean'],
                'profiles' => ['required', 'array'],
                'profiles.*' => ['exists:profiles,id'],
            ]);

            DB::beginTransaction();

            $menu->update([
                'parent_id' => $validated['parent_id'] ?? null,
                'order_no' => $validated['order_no'],
                'name' => $validated['name'],
                'icon' => $validated['icon'],
                'route' => $request->route,
                'is_active' => $validated['is_active'],
            ]);

            // Atualiza os perfis vinculados
            $menu->Profile()->sync($validated['profiles']);

            DB::commit();

            Alert::success('Atualizar', 'Menu atualizado com sucesso!');
            return redirect()->route('menu.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);
            Alert::warning('Salvar', 'Algo deu errado. Tente novamente! ' . $e->getCode() . " => " . $e->getMessage());
            return back()->withInput();
        }
    }


    public function destroy(Menu $menu)
    {
        try {
            DB::beginTransaction();

            // Remove os relacionamentos com Profile
            $menu->Profile()->detach();

            // Soft delete (ou hard delete se não usar SoftDeletes)
            $menu->delete();

            DB::commit();

            Alert::success('Remover', 'Menu removido com sucesso!');
            return redirect()->route('menu.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);
            Alert::warning('Remover', 'Erro ao remover o menu.');
            return back();
        }
    }
}
