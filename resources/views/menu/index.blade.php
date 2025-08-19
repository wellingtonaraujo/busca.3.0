<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('home') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-500 flex items-center">
                        <i class="ti ti-home"></i>
                    </button>
                </a>
                <button class="bg-sky-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-sky-600 flex items-center">
                    <i class="ti ti-menu-2"></i>
                </button>
                <span>[ADMIN - MENU]</span>
            </div>

            <div class="flex items-center gap-1">
                <x-content-header-buttons route="menu.create" icon="ti ti-plus" bgCollor='bg-green-500' hoverBgCollor='hover:bg-green-600' textCollor="text-white" title="Criar um novo menu" />
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
                                <th class="px-4 py-2 font-semibold">Icon</th>
                                <th class="px-4 py-2 font-semibold">Parent</th>
                                <th class="px-4 py-2 font-semibold">Order</th>
                                <th class="px-4 py-2 font-semibold">Status</th>
                                <th class="px-4 py-2 font-semibold">Perfil</th>
                                <th class="px-4 py-2 font-semibold">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $model)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $model->name }}</td>
                                    <td class="px-4 py-2"><i class="{{ $model->icon }}"></i></td>
                                    <td class="px-4 py-2">{{ optional($model->parent)->name }}</td>
                                    <td class="px-4 py-2">{{ $model->order_no }}</td>
                                    <td class="px-4 py-2 {{ $model->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $model->is_active ? 'Ativo' : 'Inativo' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @foreach ($model->profileMenu as $item)
                                            {{ $item->profile->id }} {{ $item->profile->name }} <br>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2 flex items-center space-x-2">@include('menu.action')</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
