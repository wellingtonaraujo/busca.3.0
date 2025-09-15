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
                                <th class="px-4 py-2 font-semibold">id</th>
                                <th class="px-4 py-2 font-semibold">Nome</th>
                                <th class="px-4 py-2 font-semibold">Foto</th>
                                <th class="px-4 py-2 font-semibold">CPF</th>
                                <th class="px-4 py-2 font-semibold">matricula</th>
                                <th class="px-4 py-2 font-semibold">Entidade</th>
                                <th class="px-4 py-2 font-semibold">Contato</th>
                                <th class="px-4 py-2 font-semibold">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pessoas as $model)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $model->id }}</td>
                                    <td class="px-4 py-2">
                                        @if ($model->foto_perfil)
                                            @php
                                                $finfo = new finfo(FILEINFO_MIME_TYPE);
                                                $mime = $finfo->buffer($model->foto_perfil);
                                            @endphp
                                            <img src="data:{{ $mime }};base64,{{ base64_encode($model->foto_perfil) }}"
                                                alt="Foto de {{ $model->nome }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <img src="{{ asset('assets/images/user.png') }}" alt="Sem Foto"
                                                class="w-10 h-10 rounded-full object-cover">
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $model->nome }}</td>
                                    <td class="px-4 py-2">{{ $model->cpf }}</td>
                                    <td class="px-4 py-2">{{ $model->matricula }}</td>
                                    <td class="px-4 py-2">{{ optional($model->entidade)->sigla }}</td>
                                    <td class="px-4 py-2">{{ $model->celular }}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">@include('pessoa.action')</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
