@foreach ($pessoas as $model)
    <div class="bg-gray-800 text-gray-200 p-4 rounded-lg shadow-md border border-gray-700">
        <div class="flex justify-between mb-1">
            <span class="font-semibold">#:</span>
            <span>{{ $loop->iteration }}</span>
        </div>
        <div class="flex justify-between mb-1">
            <span class="font-semibold">Nome:</span>
            <span class="truncate max-w-xs" title="{{ $model->nome }}">{{ $model->nome }}</span>
        </div>
        <div class="flex justify-between mb-1">
            <span class="font-semibold">Alcunha:</span>
            <span>{{ $model->alcunha }}</span>
        </div>
        <div class="flex justify-between mb-1">
            <span class="font-semibold">Contato: </span>
            @if ($contatos = $model->contatos)
                <div class="flex flex-col items-start space-y-2" style="text-align: left">
                    @foreach ($contatos as $contato)
                        <details class="w-full">
                            <summary class="cursor-pointer text-orange-400 text-left">
                                @if (!is_null($contato->nome))
                                    ({{ optional($contato)->vinculadoTipo?->descricao }})
                                    {{ $contato->nome }}
                                @else
                                    PESSOAL
                                @endif
                            </summary>

                            <div class="mt-1 bg-gray-800 p-2 rounded text-yellow-300 w-full text-left">
                                {{-- {{ optional($contato)->contatoTipo?->descricao }} --}}
                                <strong>{{ $contato->contato }}</strong>
                            </div>
                        </details>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex justify-between mb-1">
            <span class="font-semibold">Custodiado:</span>
            @if ($custodiado = $model->custodiado)
                <div class="row mt-1 bg-gray-800 p-2 rounded">
                    Regime:<BR>
                    <span>{{ optional($model->custodiado)->regime?->descricao ?? 'NÃO DEFINIDO' }}</span>
                </div>
                <div class="row mt-1 bg-gray-800 p-2 rounded text-yellow-500">
                    Situação:<BR>
                    <span>{{ optional($model->custodiado)->situacaoAtual?->descricao }}</span>
                </div>
            @endif
        </div>
        <div class="flex justify-between mb-1">
            <span class="font-semibold">Vinculado:</span>
            <span class="text-green-500">{{ optional($model->vinculado)->vinculadoStatus?->descricao ?? 'NÃO' }}</span>
        </div>
        <div class="flex justify-start mt-2 space-x-2">
            @include('search.action')
        </div>
    </div>
@endforeach
