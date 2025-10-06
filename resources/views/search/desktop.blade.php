<table class="min-w-[600px] w-full text-sm text-left border-collapse">
    <thead class="bg-gray-800 text-gray-200 border-b border-gray-700">
        <tr>
            <th class="px-4 py-2 font-semibold">#</th>
            <th class="px-4 py-2 font-semibold">Id</th>
            <th class="px-4 py-2 font-semibold">Nome</th>
            <th class="px-4 py-2 font-semibold">Alcunha</th>
            <th class="px-4 py-2 font-semibold">Contato</th>
            <th class="px-4 py-2 font-semibold">Documentos</th>
            <th class="px-4 py-2 font-semibold">Custodiado</th>
            <th class="px-4 py-2 font-semibold">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pessoas as $model)
            <tr class="border-b border-gray-800 hover:bg-gray-700 transition-colors">
                <td class="px-4 py-2 align-top">{{ $loop->iteration }}</td>
                <td class="px-4 py-2 align-top">{{ $model->id_formatado }}</td>
                <td class="px-4 py-2 truncate max-w-xs align-top" title="{{ $model->nome }}">
                    {{ $model->nome }}</td>
                <td class="px-4 py-2 align-top">
                    @if (is_null($model->alcunha) || empty($model->alcunha))
                        <span class="text-2xl text-red-500">-</span>
                    @else
                        {{ $model->alcunha }}
                    @endif
                </td>
                <td class="px-4 py-2 align-top text-left">
                    @if ($contatos = $model->contatos)

                        @foreach ($contatos as $contato)
                            {{-- <div class="mt-1 bg-gray-800 p-2 rounded text-yellow-300"> --}}
                                @if (isset($contato->nome))
                                    @if (!is_null($contato->nome))
                                        ({{ optional($contato)->vinculadoTipo?->descricao }})
                                        {{ $contato->nome }}
                                    @endif
                                    <strong class="text-yellow-300">{{ $contato->contato }}</strong>
                                @else
                                    <strong class="text-yellow-300">{{ $contato->contato }}</strong>
                                @endif
                            {{-- </div> --}}
                        @endforeach
                    @else
                        Nenhum
                    @endif
                </td>

                <td class="px-4 py-2 align-top text-left">
                    @if ($documentos = $model->documentos)
                        @foreach ($documentos as $documento)
                            <p>{{ getDocumento($documento, $model->origem)}}</p>
                        @endforeach
                    @endif
                </td>

                <td class="px-4 py-2 align-top">
                    @if ($model->origem == 'nova')
                        @php
                            $custodiado = $model->custodiado;
                        @endphp
                    @else
                        @php
                            $custodiado = !empty($model->custodiadoAntigo) ? $model->custodiadoAntigo : false;
                        @endphp
                    @endif

                    @if ($custodiado)
                        @if (!is_null($custodiado->regime))
                            {{ optional($custodiado)->regime?->descricao }}<br>
                        @endif
                        @if (!is_null($custodiado->situacaoAtual))
                            {{ optional($custodiado)->situacaoAtual?->descricao }}
                        @endif
                    @else
                        <span class="text-2xl text-red-500">-</span>
                    @endif
                </td>
                <td class="px-4 py-2 flex items-center space-x-2">
                    @include('search.action')
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
