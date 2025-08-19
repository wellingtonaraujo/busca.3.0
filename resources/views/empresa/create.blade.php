<x-layouts.auth_layout>
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('menu.index') }}">
                    <button
                        class="bg-gray-300 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-400 flex items-center">
                        <i class="ti ti-menu-2"></i>
                    </button>
                </a>
                <button class="bg-gray-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-600 flex items-center">
                    <i class="ti ti-plus"></i>
                </button>
                <span>[ADMIN - MENU - NOVO]</span>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto">
                    <span class="text-2xl">Dados do novo menu</span>
                </div>

                <form action="{{ !empty($menu) ? route('menu.update', $menu->id) : route('menu.store') }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (!empty($menu))
                        @method('PUT')
                    @endif
                    <!-- parent_id -->
                    <div>
                        <label for="parent_id" class="block font-medium">Menu Pai <span
                                class="text-red-500">*</span></label>
                        <x-select-tw name="parent_id" :options="$parents->toArray()" :selected="$menu->parent_id" placeholder="-- Nenhum --"
                            title="Selecione um menu PAI" required />
                    </div>

                    <!-- order_no -->
                    <div>
                        <label for="order_no" class="block font-medium">Ordem do menu <span
                                class="text-red-500">*</span></label>
                        {{-- teste input-tw --}}
                        <x-input-tw type="number" name="order_no" :value="isset($menu) ? $menu->order_no : null" placeholder="-- Nenhum --"
                            title="Ordem em que vai aparecer no menu" required />
                    </div>

                    <!-- name -->
                    <div>
                        <label for="name" class="block font-medium">Nome do Menu <span
                                class="text-red-500">*</span></label>
                        <x-input-tw type="text" name="name" placeholder="-- Nenhum --"
                            title="Qual será o nome deste menu" required :value="isset($menu) ? $menu->name : null" />
                    </div>

                    <!-- icon -->
                    <div>
                        <label for="icon" class="block font-medium">Ícone (classe) <span
                                class="text-red-500">*</span></label>
                        <x-input-tw type="text" name="icon" placeholder="ti ti-"
                            title="Icone do menu Ex: ti ti-check" required :value="isset($menu) ? $menu->icon : null" />
                    </div>

                    <!-- route -->
                    <div>
                        <label for="route" class="block font-medium">Rota</label>
                        <x-input-tw type="text" name="route" placeholder="nome da rota"
                            title="Rota que será invocada ao prescionar o menu. Ex: home" :value="isset($menu) ? $menu->route : null" />
                    </div>

                    <!-- is_active -->
                    <div>
                        <label for="is_active" class="block font-medium">Está ativo? <span
                                class="text-red-500">*</span></label>
                        <x-select-tw name="is_active" :options="[]" placeholder="-- Nenhum --"
                            title="Selecione um menu PAI" required :value="isset($menu) ? $menu->is_active : null" />
                    </div>

                    <div>
                        <label for="profile_id" class="block font-medium">Perfil de acesso <span
                                class="text-red-500">*</span></label>
                        <x-select-tw name="profiles[]" :options="$profiles" placeholder="-- Selecione um perfil --"
                            title="Selecione um ou mais perfis para ter acesso a este menu" required />
                    </div>

                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="{{ route('menu.index') }}">
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
