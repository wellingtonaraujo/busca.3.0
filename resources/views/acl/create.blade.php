<x-layouts.auth_layout>
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
                <a href="{{ route('acl.index') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center"
                        title="Voltar para perfil do usuário">
                        <i class="ti ti-user-shield"></i>
                    </button>
                </a>
                <button class="bg-sky-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-sky-600 flex items-center">
                    <i class="ti ti-plus"></i>
                </button>
                <span>[ADMIN - PROFILE - ACL - {{ isset($acl) ? 'EDIT' : 'NOVO' }}]</span>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto">
                    <span class="text-2xl">Dados da nova ACL</span>
                </div>
                <form action="{{ isset($acl) ? route('acl.update', $acl->id) : route('acl.store') }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (!empty($acl))
                        @method('PUT')
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <x-input-tw type="text" name="name" :value="old('nome', isset($acl) ? Str::upper($acl->name) : null)" placeholder="nome ou nomeSobrenome"
                            title="Nome da acl - ex: teste ou testeTeste" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <x-input-tw type="text" name="descricao" :value="old('descricao', isset($acl) ? $acl->descricao : null)" placeholder="-- Nenhum --"
                            title="Descrição da acl" required />
                        <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                    </div>

                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="{{ route('acl.index') }}">
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
