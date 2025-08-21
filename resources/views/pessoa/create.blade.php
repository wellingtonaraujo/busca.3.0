<x-layouts.auth_layout>
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('pessoa.index') }}">
                    <button
                        class="bg-gray-300 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-400 flex items-center">
                        <i class="ti ti-shield"></i>
                    </button>
                </a>
                <button class="bg-gray-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-gray-600 flex items-center">
                    <i class="ti ti-plus"></i>
                </button>
                <span>[ADMIN - PESSOA - {{ isset($profile) ? 'EDIT' : 'NOVO' }}]</span>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto">
                    <span class="text-2xl">Dados da nova pessoa</span>
                </div>

                <form action="{{ !empty($pessoa) ? route('pessoa.update', $pessoa->id) : route('pessoa.store') }}"
                    method="POST" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @csrf
                    @if (!empty($pessoa))
                        @method('PUT')
                    @endif

                    {{-- Coluna da foto --}}
                    <div class="flex flex-col items-center justify-start space-y-4">
                        <div class="relative">
                            @if (!empty($pessoa->foto_perfil))
                                <img src="data:image/jpeg;base64,{{ base64_encode($pessoa->foto_perfil) }}"
                                    alt="Foto de Perfil"
                                    class="w-40 h-40 rounded-full object-cover shadow-lg border-4 border-gray-200" />
                            @else
                                <div
                                    class="w-40 h-40 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 shadow border-4 border-gray-200">
                                    Sem Foto
                                </div>
                            @endif

                            {{-- Botão de upload --}}
                            <label for="foto_perfil"
                                class="absolute bottom-2 right-2 cursor-pointer bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-green-700">
                                Alterar
                            </label>
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" class="hidden" />
                        </div>
                        <span class="text-gray-700 font-medium">Foto de Perfil</span>
                    </div>

                    {{-- Coluna do formulário --}}
                    <div class="col-span-2 space-y-4">
                        <h2 class="text-2xl font-semibold mb-6 text-center">Cadastro</h2>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                            <x-input-tw type="text" name="name" :value="old('nome', isset($pessoa) ? Str::upper($pessoa->name) : null)"
                                placeholder="João dos Santos Filho" title="Nome da pessoa - ex: Fulano de Tal"
                                required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <x-select-input :options="$sexoOptions" :selected="isset($menu) ? $menu->sexo_id : null" class="text-dark" required
                                name="sexo_id" :selected="old('sexo_id', isset($menu) ? $menu->sexo_id : null)" autofocus autocomplete="menu" required />
                            <x-input-error class="mt-2" :messages="$errors->get('sexo_id')" />
                        </div>

                        <div class="flex gap-2 pt-4 justify-end">
                            <!-- Botão de voltar -->
                            <a href="#">
                                <button type="button"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700">
                                    Voltar
                                </button>
                            </a>
                            {{-- Botão salvar --}}
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
</x-layouts.auth_layout>
