<tr class="border-b border-gray-800 hover:bg-gray-700 transition-colors">
    <td class="px-4 py-2 align-top">{{ $loopIndex }}</td>
    <td class="px-4 py-2 align-top">{{ $model->id_formatado }}</td>
    <td class="px-4 py-2 truncate max-w-xs align-top" title="{{ $model->nome }}">
        {{ $model->nome }}
    </td>
    <td class="px-4 py-2 align-top">
        {{ $model->alcunha ?: '—' }}
    </td>

    {{-- Contatos --}}
    <td class="px-4 py-2 align-top text-left">
        @forelse ($model->contatos as $contato)
            @if (!empty($contato->nome))
                ({{ optional($contato->vinculadoTipo)->descricao }}) {{ $contato->nome }}
            @endif
            <strong class="text-yellow-300">{{ $contato->contato }}</strong><br>
        @empty
            <span class="text-2xl text-red-500">-</span>
        @endforelse
    </td>

    {{-- Documentos --}}
    <td class="px-4 py-2 align-top text-left">
        @forelse ($model->documentos as $documento)
            <p>{{ getDocumento($documento, $model->origem) }}</p>
        @empty
            <span class="text-2xl text-red-500">-</span>
        @endforelse
    </td>

    {{-- Custodiado --}}
    <td class="px-4 py-2 align-top">
        @php
            $custodiado = $model->origem === 'nova'
                ? $model->custodiado
                : ($model->custodiadoAntigo ?? false);
        @endphp

        @if ($custodiado)
            @if (!is_null($custodiado->regime))
                {{ optional($custodiado->regime)->descricao }}<br>
            @endif
            @if (!is_null($custodiado->situacaoAtual))
                {{ optional($custodiado->situacaoAtual)->descricao }}
            @endif
        @else
            <span class="text-2xl text-red-500">-</span>
        @endif
    </td>

    {{-- Ações --}}
    <td class="px-4 py-2 flex items-center space-x-2">
        @include('search.action')
    </td>
</tr>
