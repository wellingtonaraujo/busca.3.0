<x-layouts.auth_layout>
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('perfil.index') }}">
                    <button
                        class="bg-gray-300 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-400 flex items-center">
                        <i class="ti ti-id"></i>
                    </button>
                </a>
                <button class="bg-gray-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-600 flex items-center">
                    <i class="ti ti-plus"></i>
                </button>
                <span>[ADMIN - PROFILE - {{ isset($profile) ? 'EDIT' : 'NOVO' }}]</span>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto">
                    <span class="text-2xl">Dados do novo perfil</span>
                </div>
                <form action="{{ !empty($menu) ? route('menu.update', $menu->id) : route('menu.store') }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (!empty($menu))
                        @method('PUT')
                    @endif
                    <h2 class="text-2xl font-semibold mb-6 text-center">Cadastro</h2>

                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                    <x-input-tw type="number" name="order_no" :value="isset($menu) ? $menu->order_no : null" placeholder="-- Nenhum --"
                        title="Ordem em que vai aparecer no menu" required />
                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="#">
                            <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Voltar
                            </button>
                        </a>
                        {{-- Botão salvar --}}
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Salvar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layouts.auth_layout>
