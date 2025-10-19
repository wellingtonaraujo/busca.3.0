<x-layouts.auth_layout_basic>
    @php
        $navigaion = false;
    @endphp
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div
            class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    Custodiado: <strong>{{ $custodiado->pessoa->nome }}</strong> (#{{ $custodiado->id }})
                </div>
            </div>
        </div>

        <div
            class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">

            <!-- Aqui o 2º track é minmax(0,1fr) pra de fato usar todo o restante -->
            <div class="grid gap-4 md:grid-cols-[220px,minmax(0,1fr)] lg:grid-cols-[256px,minmax(0,1fr)] items-start">

                <!-- Coluna Foto -->
                <div class="w-[220px] lg:w-[256px] self-start">
                    <div class="aspect-[3/4] overflow-hidden rounded-md ring-1 ring-cyan-500">
                        <img src="{{ $foto }}" alt="Foto 3x4" class="w-full h-full object-cover">
                    </div>
                </div>
                <!-- Coluna Dados: ocupa TODO o restante -->
                <div class="space-y-3 text-sm self-start min-w-0 w-full">
                    <div
                        class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md border border-cyan-500 hover:shadow-cyan-500/50 w-full">
                        <div class="body w-full">
                            <div class="w-full overflow-x-auto">
                                <div class="w-full overflow-x-auto">
                                    <strong class="text-1xl">DADOS PESSOAIS</strong>
                                </div>

                                <!-- min-w-0 ajuda o truncate funcionar dentro do grid -->
                                <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                                    <dt class="text-gray-400">NOME:</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->nome ?? '—' }}</dd>

                                    <dt class="text-gray-400">DOCUMENTO:</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->alcunha ?? '—' }}</dd>

                                    <dt class="text-gray-400">NASCIMENTO:</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->nascimento ?? '—' }}</dd>

                                    <dt class="text-gray-400">IDADE:</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->idade ?? '—' }}</dd>

                                    <dt class="text-gray-400">CADASTRO DE PESSOA FÍSICA:</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->cpf ?? '—' }}</dd>

                                    <dt class="text-gray-400">REGISTRO GERAL (RG):</dt>
                                    <dd class="truncate">{{ $custodiado->pessoa->rg ?? '—' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    {{-- ENDEREÇO DO CUSTODIADO --}}
                    <div
                        class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md border border-cyan-500 hover:shadow-cyan-500/50 w-full">
                        <div class="body w-full">
                            <div class="w-full overflow-x-auto">
                                <div class="w-full overflow-x-auto">
                                    <strong class="text-1xl">ENDEREÇO E CONTATOS</strong>
                                </div>

                                <!-- min-w-0 ajuda o truncate funcionar dentro do grid -->
                                <dl class="grid grid-cols-[220px,1fr] gap-y-1 gap-x-2 min-w-0">
                                    <dt class="text-gray-400">LOGRADOURO:</dt>
                                    <dd class="truncate">Rua Fulano de Tal, 500</dd>

                                    <dt class="text-gray-400">BAIRRO:</dt>
                                    <dd class="truncate">Matagal, Macapá, Amapá</dd>
                                    {{-- {{ dd($custodiado->pessoa->contatos) }}
                                    @foreach ($contatos as $item)
                                        <dt class="text-gray-400">LOGRADOURO:</dt>
                                        <dd class="truncate">Rua Fulano de Tal, 500</dd>
                                    @endforeach --}}
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </x-layouts.auth_layout>
