<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[600px] w-full text-sm text-left border-collapse datatables">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-2 font-semibold">Id</th>
                                <th class="px-4 py-2 font-semibold">Foto</th>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Cpf</th>
                                <th class="px-4 py-2 font-semibold">Entidade</th>
                                <th class="px-4 py-2 font-semibold">Departamento</th>
                                <th class="px-4 py-2 font-semibold">Função</th>
                                <th class="px-4 py-2 font-semibold">Status</th>
                                <th class="px-4 py-2 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $model)
                                @php $rowClass = $model->user_status_id == 2 ? 'bg-red-200 text-red-900' : ''; @endphp
                                <tr class="border-b {{ $rowClass }} hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $model->id }}</td>
                                    <td class="px-4 py-2">
                                        @if ($model->pessoa->foto_perfil)
                                            <img src="data:image/jpeg;base64,{{ base64_encode($model->pessoa->foto_perfil) }}"
                                                alt="Foto de {{ $model->pessoa->nome }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <img src="{{ asset('assets/images/user.png') }}" alt="Sem Foto"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $model->pessoa->nome }}</td>
                                    <td class="px-4 py-2">{{ $model->pessoa->cpf }}</td>
                                    <td class="px-4 py-2">{{ optional($model->entidade)->sigla }}</td>
                                    <td class="px-4 py-2">{{ $model->departamento }}</td>
                                    <td class="px-4 py-2">{{ $model->funcao }}</td>
                                    <td class="px-4 py-2">{{ $model->userStatus->descricao }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">@include('user.action')</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
