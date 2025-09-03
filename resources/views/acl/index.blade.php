<x-layouts.auth_layout>
    <div class="">

        <x-page-header title="Controle de Acesso" :breadcrumbs="[
            ['label' => 'Home', 'link' => route('home'), 'icon' => 'ti ti-home'],
            ['label' => 'Admin', 'icon' => 'ti ti-settings'],
            ['label' => 'Perfil', 'link' => route('profile.index'), 'icon' => 'ti ti-id']]"
            :buttons="[
            [
                'icon' => 'ti ti-plus',
                'bg' => 'bg-black',
                'text' => 'text-yellow-200',
                'hover' => 'bg-yellow-600',
                'title' => 'Novo registro',
            ],
        ]" />

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
