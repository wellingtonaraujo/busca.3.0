<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div class="flex gap-6 p-6 mt-6 bg-gray-200 shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[600px] w-full text-sm text-left border-collapse datatables">
                        <thead class="bg-gray-200 text-indigo-950 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-2 font-semibold">#</th>
                                <th class="px-4 py-2 font-semibold">Icon</th>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Rota</th>
                                <th class="px-4 py-2 font-semibold">Parent</th>
                                <th class="px-4 py-2 font-semibold">Status</th>
                                <th class="px-4 py-2 font-semibold">Perfil</th>
                                <th class="px-4 py-2 font-semibold">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $model)
                                <tr class="border-b hover:bg-gray-50 text-indigo-950">
                                    <td class="px-4 py-2">{{ $model->id }}</td>
                                    <td class="px-4 py-2"><i class="{{ $model->icon }}"></i></td>
                                    <td class="px-4 py-2">{{ $model->name }}</td>
                                    <td class="px-4 py-2">{{ $model->route }}</td>
                                    <td class="px-4 py-2">{{ optional($model->parent)->name }}</td>
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
