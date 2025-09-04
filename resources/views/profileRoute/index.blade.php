<x-layouts.auth_layout>
    {{-- @include('sweetalert::alert') --}}
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            @if ($profile->id == 1)
                <div class="body w-full">
                    <div class="w-full p-3 text-1xl overflow-x-auto text-black rounded-xl border border-gray-500 card">
                        <strong>
                            <i class="ti ti-info-circle text-3xl"></i>
                            {{ $profile->name }}
                            <i class="ti ti-arrow-right"></i>
                            {{ optional($profile)->descricao ?? ' ---- ' }}
                            <p>Para este perfil todas as rotas são permitidas.</p>
                        </strong>
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
                    <div class="w-full p-3 text-1xl overflow-x-auto text-black rounded-xl border border-gray-500 card">
                        <strong>
                            <i class="ti ti-info-circle text-3xl"></i>
                            {{ $profile->name }}
                            <i class="ti ti-arrow-right"></i>
                            {{ optional($profile)->descricao ?? ' ---- ' }}
                        </strong>
                    </div>

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
