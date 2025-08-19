@if (Auth::user()->routeAccess("$route.edit"))
    <a href="{{ route('profile.edit', $model->id) }}" class="text-blue-500 hover:text-blue-700"
        title="Editar o perfil do usuário">
        <i class="ti ti-edit text-2xl"></i>
    </a>
@endif

@if (Auth::user()->routeAccess('profileRoute.index'))
    <a href="{{ route('profileRoute.index',  ['profile_id'=>$model->id]) }}" class="text-blue-500 hover:text-blue-700"
        title="Rotas permitidas para este usuário...">
        <i class="ti ti-user-shield text-2xl"></i>
    </a>
@endif

@if (Auth::user()->routeAccess("$route.destroy"))
    <form action="{{ route('profile.destroy', $model->id) }}" method="POST"
        onsubmit="return confirm('Tem certeza que deseja excluir?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700" title="Excluir perfil do usuário">
            <i class="ti ti-trash text-2xl"></i>
        </button>
    </form>
@endif
