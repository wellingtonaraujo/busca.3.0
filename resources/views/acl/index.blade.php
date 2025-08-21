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
                <!-- Campo de busca, visível em todas as telas -->
                <div class="mb-4 flex items-center justify-end md:justify-start">
                    <label for="search" class="sr-only">Buscar:</label>
                    <span class="text-gray-700 font-semibold mr-2 hidden md:inline">Buscar:</span>
                    <input type="text" id="search" placeholder="Pesquisar..." class="border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-auto">
                </div>

                <!-- Tabela para desktop (escondida em telas pequenas) -->
                <div class="w-full overflow-x-auto hidden md:block">
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
                </div>

                <!-- Cartões para mobile (escondidos em telas grandes) -->
                <div class="md:hidden">
                    @foreach ($acls as $model)
                        <div class="bg-white shadow-md rounded-lg p-4 mb-4 border border-gray-200">
                            {{-- Seção de dados principais --}}
                            <div class="mb-3">
                                <div class="font-bold text-gray-800 text-base flex justify-between items-center">
                                    <span>Nome: <span class="font-normal text-gray-700">{{ $model->name }}</span></span>
                                    {{-- Adicione o número do item aqui se desejar, como no seu print --}}
                                    {{-- Exemplo: <span class="text-xs font-semibold text-gray-500">#{{ $loop->iteration }}</span> --}}
                                </div>
                                <div class="font-bold text-gray-800 text-base">
                                    Descrição: <span class="font-normal text-gray-700">{{ $model->descricao }}</span>
                                </div>
                            </div>

                            {{-- Seção de Status e Opções --}}
                            <div class="border-t border-gray-200 pt-3 mt-3">
                                <div class="flex items-center mb-2">
                                    <span class="font-bold text-gray-800 mr-2">Status:</span>
                                    <i class="ti ti-thumb-down text-red-500 text-xl"></i> {{-- Ícone de exemplo --}}
                                    {{-- Substitua pelo status real do seu modelo se houver: <span class="text-gray-700">{{ $model->status_text }}</span> --}}
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-800 mr-2">Opções:</span>
                                    <div class="flex space-x-2">
                                        @include('acl.action')
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-center">
                    {{ $acls->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
