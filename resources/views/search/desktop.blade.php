<table class="min-w-[720px] w-full text-sm text-left border-collapse">
    <thead class="bg-gray-800 text-gray-200 border-b border-gray-700">
        <tr>
            <th class="px-4 py-2 font-semibold">#</th>
            <th class="px-4 py-2 font-semibold">ID</th>
            <th class="px-4 py-2 font-semibold">Nome</th>
            <th class="px-4 py-2 font-semibold">Alcunha</th>
            <th class="px-4 py-2 font-semibold">Contato</th>
            <th class="px-4 py-2 font-semibold">Documentos</th>
            <th class="px-4 py-2 font-semibold">Custodiado</th>
            <th class="px-4 py-2 font-semibold">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pessoas as $model)
            @php
                $custodiado = $model->custodiado;
                $custodiado_id = optional( $model->custodiado)->id ?? null;
                $contatos = $model->contatos ?? collect();
                $documentos = $model->documentos ?? collect();
            @endphp

            <tr class="border-b border-gray-800 hover:bg-gray-700 transition-colors align-top">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $model->id_formatado }}</td>

                <td class="px-4 py-2 max-w-xs">
                    <span class="truncate block" title="{{ $model->nome }}">{{ $model->nome }}</span>
                </td>

                <td class="px-4 py-2">
                    @if (blank($model->alcunha))
                        <span class="text-2xl leading-none text-red-500">-</span>
                    @else
                        {{ $model->alcunha }}
                    @endif
                </td>

                {{-- Contatos --}}
                <td class="px-4 py-2">
                    @forelse ($contatos as $c)
                        <div class="mt-1">
                            @if (!is_null($c->nome ?? null))
                                <span class="text-gray-300">
                                    ({{ optional($c->vinculadoTipo)->descricao }}) {{ $c->nome }}
                                </span>
                            @endif
                            <strong class="text-yellow-300 block">{{ $c->contato }}</strong>
                        </div>
                    @empty
                        <span class="text-gray-400">Nenhum</span>
                    @endforelse
                </td>

                {{-- Documentos --}}
                <td class="px-4 py-2">
                    {{-- @forelse ($documentos as $doc)
                        <p>{{ $doc->documentoTipo }} {{ $model->origem == 'nova' ? $doc->documento_numero : $doc->numero_documento }}</p>
                    @empty --}}
                        <span class="text-gray-400">—</span>
                    {{-- @endforelse --}}
                </td>

                {{-- Custodiado --}}
                <td class="px-4 py-2">
                    @if ($custodiado)
                        <p>Regime: {{ optional($custodiado->regime)->descricao ?? 'NÃO DEFINIDO' }}</p>
                        Situação: {{ optional($custodiado->situacaoAtual)->descricao ?? 'NÃO DEFINIDO' }}
                    @endif
                </td>

                {{-- Ações --}}
                <td class="px-4 py-2">
                    <div class="flex items-center gap-2">
                        @include('search.action', ['model'=>$model, 'custodiado_id'=>optional($model->custodiado)->id ?? null, 'origem'=>$model->origem, 'routeSearch'=>$routeSearch])
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-400">Nenhum registro encontrado.</td>
            </tr>
        @endforelse
    </tbody>
</table>
