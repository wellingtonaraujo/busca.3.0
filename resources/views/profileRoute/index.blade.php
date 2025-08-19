<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('home') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center"
                        title="Voltar para home">
                        <i class="ti ti-home"></i>
                    </button>
                </a>
                <a href="{{ route('profile.index') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center"
                        title="Voltar para perfil do usuário">
                        <i class="ti ti-id"></i>
                    </button>
                </a>
                <button class="bg-sky-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-sky-600 flex items-center">
                    <i class="ti ti-user-shield"></i>
                </button>
                <span>[ADMIN - ROTAS PERMITIDAS]</span>
            </div>

            <div class="flex items-center gap-1">
                <a href="{{ route('menu.create') }}">
                    <button
                        class="bg-green-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-green-600 flex items-center">
                        <i class="ti ti-plus"></i>
                    </button>
                </a>

                <button class="bg-blue-500 text-white text-2xl px-2 py-1 rounded-r hover:bg-blue-600 flex items-center">
                    <i class="ti ti-printer"></i>
                </button>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            @if ($profile->id == 1)
                <div class="body w-full">
                    <div class="flex p-3 text-1xl bg-cyan-300 hover:bg-cyan-400 rounded-xl card">
                        <strong><i class="ti ti-info-circle text-3xl"></i> {{ $profile->name }} <i class="ti ti-arrow-right"></i> Para este perfil todas as rotas
                            são permitidas.</strong>
                    </div>
                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="{{ route('profile.index') }}">
                            <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Voltar
                            </button>
                        </a>
                    </div>
                </div>
            @else
                <div class="body w-full">
                    @include('profileRoute.list')
                </div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selecionarTodos = document.getElementById('selecionar-todos');
                const checkboxes = document.querySelectorAll('.checkbox-rota');

                selecionarTodos.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selecionarTodos.checked;
                    });
                });
            });
        </script>

</x-layouts.auth_layout>
