<x-layouts.auth_layout>
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('profile.index') }}">
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
                <form action="{{ isset($profile) ? route('profile.update', $profile->id) : route('profile.store') }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (isset($profile))
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="block font-medium">Nome <span class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="text" name="name" placeholder="alguma coisa"
                            title="Nome do perfil do usuário" :value="isset($profile) ? $profile->name : null" />
                    </div>

                    <div>
                        <label for="descricao" class="block font-medium">Descrição <span class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="text" name="descricao" placeholder="alguma coisa"
                            title="Descrição do perfil do usuário" :value="isset($profile) ? $profile->descricao : null" />
                    </div>

                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="{{ route('profile.index') }}">
                            <button type="button" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Voltar
                            </button>
                        </a>
                        {{-- Botão salvar --}}
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            @if (isset($profile))
                                Atualizar
                            @else
                                Salvar
                            @endif
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layouts.auth_layout>
