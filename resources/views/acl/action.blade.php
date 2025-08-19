@if (Auth::user()->routeAccess("$route.edit"))
    <a href="{{ route("$route.edit", $model->id) }}" class="text-blue-500 hover:text-blue-700"
        title="Editar o acl do sistema">
        <i class="ti ti-edit text-2xl"></i>
    </a>
@endif

@if (Auth::user()->routeAccess("$route.destroy"))
    <form action="{{ route("$route.destroy", $model->id) }}" method="POST"
        onsubmit="return confirm('Tem certeza que deseja excluir?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700" title="Excluir acl do sistema.">
            <i class="ti ti-trash text-2xl"></i>
        </button>
    </form>
@endif
