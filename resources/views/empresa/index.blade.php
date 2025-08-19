<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <i class="ti ti-building-factory"></i>
                <span>[ADMIN - EMPRESAS]</span>
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ route('empresa.create') }}">
                    <button
                        class="bg-green-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-green-600 flex items-center">
                        <i class="ti ti-plus"></i>
                    </button>
                </a>

                <button class="bg-blue-500 text-white text-2xl px-2 py-1 rounded-r hover:bg-blue-600 flex items-center">
                    <i class="ti ti-printer"></i>
                </button>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[600px] w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-2 font-semibold">CNPJ</th>
                                <th class="px-4 py-2 font-semibold">Razão</th>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Cidade/UF</th>
                                <th class="px-4 py-2 font-semibold">Fone</th>
                                <th class="px-4 py-2 font-semibold">Responsavel</th>
                                <th class="px-4 py-2 font-semibold">Contato</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($empresas as $empresa)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $empresa->cnpj }}</td>
                                    <td class="px-4 py-2"><i class="{{ $empresa->razao }}"></i></td>
                                    <td class="px-4 py-2">{{ $empresa->nome }}</td>
                                    <td class="px-4 py-2">{{ $empresa->cidade }}/{{ $empresa->uf }}</td>
                                    <td class="px-4 py-2">{{ $empresa->fone }}</td>
                                    <td class="px-4 py-2">{{ $empresa->responsavel }}</td>
                                    <td class="px-4 py-2">{{ $empresa->responsavel_contato }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">
                                        <a href="{{ route('empresa.edit', $empresa->id) }}"
                                            class="text-blue-500 hover:text-blue-700">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('empresa.destroy', $empresa->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
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
