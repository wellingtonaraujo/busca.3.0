<x-layouts.auth_layout>
    <div class="">

        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

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
                                        @include('acl.action')
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
