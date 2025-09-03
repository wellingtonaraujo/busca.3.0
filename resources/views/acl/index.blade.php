<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-yellow-500 text-black shadow-md rounded-xl w-full text-sm py-4 px-6">
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
                <span><strong>[ADMIN - PERFIL - ACL]</strong></span>
            </div>

            <div class="flex items-center gap-1">
                {{-- <x-content-header-buttons :route="route('acl.create')" icon="ti ti-plus" bgCollor='bg-green-500'
                    hoverBgCollor='hover:bg-green-600' textCollor="text-white" title="Criar uma nova acl" /> --}}
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-gray-200 shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[600px] w-full text-sm text-left border-collapse datatables">
                        <thead class="bg-gray-100 text-indigo-950 border-b border-gray-300 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 font-semibold">Id</th>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Descrição</th>
                                <th class="px-4 py-2 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($acls as $model)
                                <tr class="border-b hover:bg-gray-50 text-black">
                                    <td class="px-4 py-2">{{ $model->id }}</td>
                                    <td class="px-4 py-2 truncate max-w-xs" title="{{ $model->name }}">
                                        {{ $model->name }}
                                    </td>
                                    <td class="px-4 py-2">{{ $model->descricao }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">
                                        @include('profile.action')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
