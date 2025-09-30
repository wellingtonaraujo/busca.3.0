<x-layouts.auth_layout>
    <div class="bg-gray-900 min-h-screen">
        <div
            class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md max-w-6xl mx-auto border border-cyan-500 hover:shadow-cyan-500/50">
            <!-- Título -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>
                    Buscar Pessoa
                </h2>
                <button class="text-gray-400 hover:text-white text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Voltar
                </button>
            </div>

            <!-- Formulário -->
            <form action="{{ route('search') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form-input-text name="nome" placeholder="Nome" :value="$parametros['nome'] ?? ''" />
                    <x-form-input-text name="apelido" placeholder="Apelido / Codnome" :value="$parametros['apelido'] ?? ''" />
                    <x-form-input-select name="documento_tipo_id" :options="[1 => 'Carteira de Identidade', 2 => 'CPF - Cadastro de pessoa física']" placeholder="Tipo de documento"
                        :value="null" :value="$parametros['documento_tipo_id'] ?? 2" />
                    <x-form-input-text name="documento_numero" placeholder="Número do documento" :value="$parametros['documento_numero'] ?? ''" />
                    {{-- <x-form-input-text name="rg_num" placeholder="Nº RG (Só números)" :value="$parametros['rg_num'] ?? ''" /> --}}
                    <x-form-input-text name="contato" placeholder="Contato (Só números)" :value="$parametros['contato'] ?? ''" />
                    <x-form-input-text name="contato_nome" placeholder="Nome do contato (Ex. Nome da Mãe)"
                        :value="$parametros['contato_nome'] ?? ''" />
                    {{-- <x-form-input-select name="regime_id" :options="$regimes" placeholder="Regime de prisão"
                        :value="$parametros['regime_id'] ?? ''" />
                    <x-form-input-select name="custodiado_situacao_atual_id" :options="$situacaoAtual"
                        placeholder="Situação pricional" :value="null" :value="$parametros['custodiado_situacao_atual_id'] ?? ''" />
                    <x-form-input-select name="order_by" :options="['id' => 'Id da pessoa', 'nome' => 'Nome da pessoa', 'regime' => 'Situação']" placeholder="Ordenar por..."
                        :value="null" :value="$parametros['order_by'] ?? ''" /> --}}
                </div>

                <!-- Botões -->
                <div class="flex gap-4 mt-6">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg shadow-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>
                        Buscar
                    </button>
                    <button type="reset"
                        class="flex items-center gap-2 px-6 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg shadow-md transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Limpar
                    </button>
                </div>
            </form>

            <!-- Dica -->
            <p class="text-xs text-gray-400 mt-4 text-center">
                Dica: se não souber a ordem de <strong>nome</strong> e <strong>sobrenome</strong>, use o coringa <span
                    class="px-1 py-0.5 bg-gray-700 rounded text-cyan-400">% </span>
                Ex.: <span class="text-cyan-400 font-semibold">João%Silva</span> retorna registros que tenham ambos, em
                qualquer
                ordem.
            </p>
        </div>

        {{-- resultado da busca --}}
        @if (!is_null($pessoas))
            <div
                class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md max-w-6xl mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
                <!-- Título do resultado -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-cyan-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <div class="inline-block whitespace-nowrap">
                            <strong>Resultado: {{ $pessoas->count() }} pessoas em
                                <strong>{{ $tempoExecucao }} segundos</strong>.
                            </strong>
                        </div>
                    </h2>
                </div>

                <!-- Tabela -->
                <div class="w-full">
                    <div class="hidden md:block w-full overflow-x-auto">
                        <!-- Tabela normal para desktop -->
                        @include('search.desktop')
                    </div>

                    <!-- Card view para mobile -->
                    <div class="md:hidden space-y-4">
                        @include('search.mobile')
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-layouts.auth_layout>
