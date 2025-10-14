@forelse ($pessoas as $model)
    @php
        $custodiado = $model->origem === 'nova'
            ? ($model->custodiado ?? null)
            : (!empty($model->custodiadoAntigo) ? $model->custodiadoAntigo : null);

        $contatos = $model->contatos ?? collect();
    @endphp

    <div class="bg-gray-800 text-gray-200 p-4 rounded-lg shadow-md border border-gray-700">
        <div class="flex justify-between mb-1">
            <span class="font-semibold">ID</span>
            <span>{{ $model->id_formatado }}</span>
        </div>

        <div class="flex justify-between mb-1">
            <span class="font-semibold">Nome:</span>
            <span class="truncate max-w-[60%]" title="{{ $model->nome }}">{{ $model->nome }}</span>
        </div>

        <div class="flex justify-between mb-1">
            <span class="font-semibold">Alcunha:</span>
            <span>{{ $model->alcunha ?? '—' }}</span>
        </div>

        <div class="mb-1">
            <span class="font-semibold">Contato:</span>
            @forelse ($contatos as $c)
                <div class="mt-2">
                    @if (!is_null($c->nome ?? null))
                        <div class="text-gray-300 text-xs">
                            ({{ optional($c->vinculadoTipo)->descricao }}) {{ $c->nome }}
                        </div>
                    @endif
                    <div class="mt-1 bg-gray-700/70 p-2 rounded text-yellow-300 w-full">
                        <strong>{{ $c->contato }}</strong>
                    </div>
                </div>
            @empty
                <div class="text-gray-400">Nenhum</div>
            @endforelse
        </div>

        <div class="mt-2">
            <span class="font-semibold">Custodiado:</span>
            @if ($custodiado)
                <div class="mt-2 space-y-2">
                    <div class="bg-gray-700/70 p-2 rounded">
                        <div class="text-xs uppercase text-gray-400">Regime</div>
                        <div>{{ optional($custodiado->regime)->descricao ?? 'NÃO DEFINIDO' }}</div>
                    </div>
                    <div class="bg-gray-700/70 p-2 rounded text-yellow-500">
                        <div class="text-xs uppercase text-gray-400">Situação</div>
                        <div>{{ optional($custodiado->situacaoAtual)->descricao ?? strtoupper($model->origem) }}</div>
                    </div>
                </div>
            @else
                <strong class="ml-2">NÃO</strong>
            @endif
        </div>

        <div class="flex justify-start mt-3 gap-2">
            @include('search.action', ['model' => $model])
        </div>
    </div>
@empty
    <div class="text-center text-gray-400">Nenhum registro encontrado.</div>
@endforelse
