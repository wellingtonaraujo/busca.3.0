<x-layouts.auth_layout_basic>
    @php($navigaion = false)

    <div class="">
        @isset($titulo)
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endisset

        <!-- Cabeçalho compacto -->
        <div class="bg-gray-900 text-gray-200 p-4 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
            <div class="flex flex-wrap items-center gap-2">
                <span>Custodiado:</span>
                <strong class="truncate max-w-[60ch]">{{ $custodiado->pessoa->nome ?? '—' }}</strong>
                <span class="opacity-70">(#{{ $custodiado->id }})</span>
                <span class="ml-auto text-xs opacity-70">Tempo: {{ $tempo_execucao ?? '—' }}</span>
            </div>
        </div>

        <!-- Corpo -->
        <div class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
            <!-- Segundo track ocupa todo o restante -->
            <div class="grid gap-4 md:grid-cols-[220px,minmax(0,1fr)] lg:grid-cols-[256px,minmax(0,1fr)] items-start">

                <!-- Coluna Foto -->
                <div class="w-[220px] lg:w-[256px] self-start">
                    <div class="aspect-[3/4] overflow-hidden rounded-md ring-1 ring-cyan-500">
                        <img
                            src="{{ $foto ?: asset('assets/images/icons/no_image.png') }}"
                            alt="Foto 3x4"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >
                    </div>
                </div>

                <!-- Coluna Dados -->
                <div class="space-y-4 text-sm self-start min-w-0 w-full">

                    {{-- DADOS PESSOAIS --}}
                    <section class="p-4 rounded-xl border border-cyan-500 hover:shadow-cyan-500/50">
                        <h2 class="text-base font-semibold mb-3">DADOS PESSOAIS</h2>
                        <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                            <dt class="text-gray-400">NOME:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->nome ?? '—' }}</dd>

                            <dt class="text-gray-400">ALCUNHA / APELIDO:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->alcunha ?? '—' }}</dd>

                            <dt class="text-gray-400">NASCIMENTO:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->nascimento_br ?? '—' }}</dd>

                            <dt class="text-gray-400">IDADE:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->idade_texto ?? ($custodiado->pessoa->idade ? $custodiado->pessoa->idade.' anos' : '—') }}</dd>

                            <dt class="text-gray-400">CPF:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->cpf ?? '—' }}</dd>

                            <dt class="text-gray-400">RG:</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->rg ?? '—' }}</dd>
                        </dl>
                    </section>

                    {{-- SITUAÇÃO PENAL (REGIME + SITUAÇÃO ATUAL, SEPARADOS) --}}
                    <section class="p-4 rounded-xl border border-cyan-500 hover:shadow-cyan-500/50">
                        <h2 class="text-base font-semibold mb-3">SITUAÇÃO PENAL</h2>
                        <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                            <dt class="text-gray-400">REGIME:</dt>
                            <dd class="truncate">{{ $custodiado->regime_descricao ?? ($custodiado->regime?->descricao ?? '—') }}</dd>

                            <dt class="text-gray-400">SITUAÇÃO ATUAL:</dt>
                            <dd class="truncate">{{ $custodiado->situacao_atual_descricao ?? ($custodiado->situacaoAtual?->descricao ?? '—') }}</dd>
                        </dl>
                    </section>

                    {{-- ENDEREÇO --}}
                    <section class="p-4 rounded-xl border border-cyan-500 hover:shadow-cyan-500/50">
                        <h2 class="text-base font-semibold mb-3">ENDEREÇO</h2>
                        <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                            <dt class="text-gray-400">LOGRADOURO (COMPLETO):</dt>
                            <dd class="truncate">{{ $custodiado->pessoa->enderecoTexto }}</dd>
                        </dl>
                    </section>

                    {{-- CONTATOS --}}
                    <section class="p-4 rounded-xl border border-cyan-500 hover:shadow-cyan-500/50">
                        <h2 class="text-base font-semibold mb-3">CONTATOS</h2>
                        <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                            @forelse ($custodiado->pessoa->contatos as $contato)
                                <dt class="text-gray-400">
                                    {{ $contato->vinculadoTipo->descricao ?? 'PESSOAL' }}
                                </dt>
                                <dd class="truncate">
                                    @if($contato->nome) <span class="mr-1">{{ $contato->nome }}</span>@endif
                                    @if($contato->contatoTipo?->descricao) <span class="opacity-70 mr-1">{{ $contato->contatoTipo->descricao }}:</span>@endif
                                    <span class="mr-1">{{ $contato->contato }}</span>
                                    @if($contato->observacao) <span class="opacity-70">({{ $contato->observacao }})</span>@endif
                                </dd>
                            @empty
                                <dt class="text-gray-400">—</dt>
                                <dd class="truncate">Sem contatos cadastrados</dd>
                            @endforelse
                        </dl>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout_basic>
