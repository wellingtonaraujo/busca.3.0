<x-layouts.auth_layout>
    <div class="bg-gray-900 min-h-screen">
        <!-- Área dos botões -->
        <div class="flex justify-center gap-6 pt-6">
            <!-- Botão 1 -->
            <button
                class="relative flex flex-col items-center justify-center w-80 h-72 rounded-2xl border border-cyan-400 bg-gray-800 shadow-lg overflow-hidden transition transform hover:scale-105 hover:shadow-cyan-500/50">
                <!-- Título -->
                <div class="absolute top-4 text-white text-xl font-semibold">
                    Buscar por custodiado
                </div>
                <!-- Conteúdo central -->
                <img src="{{ asset('assets/images/prisioneiro.png') }}" alt="Custodiado"
                    class="w-40 h-40 object-contain" />
            </button>

            <!-- Botão 2 -->
            <button
                class="relative flex flex-col items-center justify-center w-80 h-72 rounded-2xl border border-cyan-400 bg-gray-800 shadow-lg overflow-hidden transition transform hover:scale-105 hover:shadow-cyan-500/50">
                <!-- Título -->
                <div class="absolute top-4 text-white text-xl font-semibold">
                    Buscar por pessoas vinculadas
                </div>
                <!-- Conteúdo central -->
                <img src="{{ asset('assets/images/vinculados.png') }}" alt="Vinculados"
                    class="w-40 h-40 object-contain" />
            </button>
        </div>

        <!-- Card de informações -->
        <div class="flex items-center justify-center p-6">
            <div class="w-full max-w-lg">
                <!-- Título -->
                <h2 class="text-center text-gray-200 text-lg font-semibold mb-3">
                    Informações de Trabalho
                </h2>

                <!-- Card com efeito hover -->
                <div
                    class="bg-gray-800 text-gray-200 rounded-xl shadow-md border border-cyan-500 p-5 space-y-2
                    transition transform hover:scale-105 hover:shadow-cyan-500/50 cursor-pointer">
                    <p>
                        Seja Bem-vindo
                        <span class="font-bold text-white"><strong
                                class="text-cyan-400">{{ Auth::user()->pessoa->nome }}.</strong></span>
                    </p>
                    <p>
                        <span class="font-semibold">Data:</span>
                        <span class="text-cyan-400">{{ date('d/m/Y') }}</span>
                    </p>
                    <p>
                        <span class="font-semibold">Hora:</span>
                        <a href="#" class="text-cyan-400 hover:underline">{{ now()->format('H:i:s') }}</a>
                    </p>

                    <p class="mt-3 font-semibold">- Informações Pessoais -</p>
                    <p>
                        <span class="font-semibold">Email:</span>
                        <span class="text-cyan-300">{{ Auth::user()->pessoa->email }}</span>
                    </p>

                    {{-- separador --}}
                    <hr class="border-t border-cyan-500 my-3">

                    <p class="text-cyan-400 font-semibold">
                        {{ Auth::user()->entidade->nome }}
                    </p>

                    <p>
                        <span class="font-semibold">Departamento:</span> <strong
                            class="text-cyan-400">{{ Auth::user()->departamento }}</strong>
                    </p>

                    <p>
                        <span class="font-semibold">Função:</span> <strong
                            class="text-cyan-400">{{ Auth::user()->funcao }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth_layout>
