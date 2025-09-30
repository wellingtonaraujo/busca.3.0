<table class="min-w-[600px] w-full text-sm text-left border-collapse">
    <thead class="bg-gray-800 text-gray-200 border-b border-gray-700">
        <tr>
            <th class="px-4 py-2 font-semibold">#</th>
            {{-- <th class="px-4 py-2 font-semibold">Id</th> --}}
            <th class="px-4 py-2 font-semibold">Nome</th>
            <th class="px-4 py-2 font-semibold">Alcunha</th>
            <th class="px-4 py-2 font-semibold">Contato</th>
            <th class="px-4 py-2 font-semibold">Custodiado</th>
            <th class="px-4 py-2 font-semibold">Vinculado</th>
            <th class="px-4 py-2 font-semibold">DB Origem</th>
            <th class="px-4 py-2 font-semibold">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pessoas as $model)
            <tr class="border-b border-gray-800 hover:bg-gray-700 transition-colors">
                <td class="px-4 py-2 align-top">{{ $loop->iteration }}</td>
                {{-- <td class="px-4 py-2 align-top">{{ $model->id }}</td> --}}
                <td class="px-4 py-2 truncate max-w-xs align-top" title="{{ $model->nome }}">
                    {{ $model->nome }}</td>
                <td class="px-4 py-2 align-top">{{ $model->alcunha }}</td>
                <td class="px-4 py-2 align-top">
                    @if ($contatos = $model->contatos)
                        @foreach ($contatos as $contato)
                            <details>
                                <summary class="cursor-pointer text-orange-400">
                                    @if (!is_null($contato->nome))
                                        ({{ optional($contato)->vinculadoTipo?->descricao }})
                                        {{ $contato->nome }}
                                    @else
                                        PESSOAL
                                    @endif
                                </summary>
                                <div class="mt-1 bg-gray-800 p-2 rounded text-yellow-300">
                                    {{ optional($contato)->contatoTipo?->descricao }}
                                    <strong>{{ $contato->contato }}</strong>
                                </div>
                            </details>
                        @endforeach
                    @else
                        Nenhum
                    @endif
                </td>
                <td class="px-4 py-2 align-top">
                    @if ($custodiado = $model->custodiado)
                        @if ($model->origem == 'antiga')
                            {{ dd($model->custodiado) }}
                        @endif
                        <details>
                            <summary class="cursor-pointer text-orange-400">
                                Regime:
                                <strong>{{ optional($model->custodiado)->regime?->descricao }}</strong>
                            </summary>
                            <div class="mt-1 bg-gray-800 p-2 rounded text-yellow-300">
                                Situação:
                                <strong>{{ optional($model->custodiado)->situacaoAtual?->descricao }}</strong>
                            </div>
                        </details>
                    @endif
                </td>
                <td class="px-4 py-2 text-green-400">
                    @if ($vinculado = $model->vinculado)
                        * Situação:
                        <strong>{{ optional($model->vinculado)->vinculadoStatus?->descricao }}</strong>
                    @endif
                </td>
                <td class="px-4 py-2 align-top">{{ $model->origem }}</td>
                <td class="px-4 py-2 flex items-center space-x-2">
                    @include('search.action')
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
