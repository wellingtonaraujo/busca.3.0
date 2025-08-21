<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('home') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center">
                        <i class="ti ti-home"></i>
                    </button>
                </a>
                <button class="bg-sky-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-sky-600 flex items-center">
                    <i class="ti ti-id"></i>
                </button>
                <span>[ADMIN - controller]</span>
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ route('menu.create') }}">
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
                                <th class="px-4 py-2 font-semibold">Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($models as $model)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $model->name }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">@include('modelo.action')</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 flex justify-center">
                        {{ $itens->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
