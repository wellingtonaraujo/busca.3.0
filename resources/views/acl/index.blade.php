<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('home') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center"
                        title="Voltar para home">
                        <i class="ti ti-home"></i>
                    </button>
                </a>
                <a href="{{ route('profile.index') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center"
                        title="Voltar para perfil do usuário">
                        <i class="ti ti-id"></i>
                    </button>
                </a>
                <button class="bg-purple-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-purple-600 flex items-center">
                    <i class="ti ti-key"></i>
                </button>
                <span>[ADMIN - PERFIL - ACL]</span>
            </div>

            <div class="flex items-center gap-1">
                <x-content-header-buttons :route="route('acl.create')" icon="ti ti-plus" bgCollor='bg-green-500'
                    hoverBgCollor='hover:bg-green-600' textCollor="text-white" title="Criar uma nova acl" />
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[600px] w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Descrição</th>
                                <th class="px-4 py-2 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($acls as $model)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $model->name }}</td>
                                    <td class="px-4 py-2">{{ $model->descricao }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">@include('acl.action')</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 flex justify-center">
                        {{ $acls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
