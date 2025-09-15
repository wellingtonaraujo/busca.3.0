@if (Auth::user()->routeAccess("$route.edit"))
    <a href="{{ route("$route.index", $log->id) }}" class="text-blue-500 hover:text-blue-700"
        title="Editar o acl do sistema">
        <i class="ti ti-edit text-2xl"></i>
    </a>
@endif
