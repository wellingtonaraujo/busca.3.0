<x-layouts.auth_layout>
    <div>
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    <table id="logs-table" class="min-w-[600px] w-full text-sm text-left border-collapse datatables">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Usuário</th>
                                <th class="px-4 py-2">Entidade</th>
                                <th class="px-4 py-2">Ação</th>
                                <th class="px-4 py-2">Tabela</th>
                                <th class="px-4 py-2">Registro</th>
                                <th class="px-4 py-2">Alterações</th>
                                <th class="px-4 py-2">IP</th>
                                <th class="px-4 py-2">Registrado</th>
                                <th class="px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                {{-- {{ dd($log->user->entidade) }} --}}
                                <tr>
                                    <td class="px-4 py-2">{{ $log->id }}</td>
                                    <td class="px-4 py-2">
                                        <details>
                                            <summary class="cursor-pointer text-blue-600">CPF:<strong>{{ $log->user->cpf }}</strong></summary>
                                            <div class="mt-1 bg-gray-50 p-2 rounded">
                                                <strong>{{ $log->user->pessoa->nome }}</strong>
                                            </div>
                                        </details>
                                    </td>
                                    <td class="px-4 py-2">{{ $log->user->entidade->sigla }}</td>
                                    <td class="px-4 py-2">{{ $log->action }}</td>
                                    <td class="px-4 py-2">{{ $log->table_name }}</td>
                                    <td class="px-4 py-2">{{ $log->record_id }}</td>
                                    <td class="px-4 py-2">
                                        @if ($log->action === 'update')
                                            <details>
                                                <summary class="cursor-pointer text-blue-600">Ver mudanças</summary>
                                                <div class="mt-1 bg-gray-50 p-2 rounded">
                                                    <strong>Antes:</strong> {{ json_encode($log->old_data) }} <br>
                                                    <strong>Depois:</strong> {{ json_encode($log->new_data) }}
                                                </div>
                                            </details>
                                        @elseif($log->action === 'create')
                                            <span class="text-green-700">{{ json_encode($log->new_data) }}</span>
                                        @elseif($log->action === 'delete')
                                            <span class="text-red-700">{{ json_encode($log->old_data) }}</span>
                                        @elseif($log->action === 'select')
                                            <span class="text-gray-700">Consulta realizada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $log->ip_address }}</td>
                                    <td class="px-4 py-2">{{ $log->created_at }}</td>
                                    <td class="px-4 py-2">@include('audit.action')</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#logs-table').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#logs-table').DataTable({
                searching: true, // habilita a pesquisa global
                paging: true, // habilita paginação
                info: true, // mostra info de registros
                lengthChange: true, // permite mudar quantidade de registros
                columnDefs: [{
                        targets: '_all',
                        searchable: true
                    } // garante que todas as colunas participem da pesquisa
                ]
            });
        });
    </script>

    </x-app-layout>
