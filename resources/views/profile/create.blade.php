<x-layouts.auth_layout>
    <div class="">
        <header class="bg-indigo-200 shadow-md rounded-xl w-full text-sm py-4 px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2 text-2xl">
                <a href="{{ route('home') }}">
                    <button
                        class="bg-slate-400 text-white text-2xl px-2 py-1 rounded-l hover:bg-slate-600 flex items-center">
                        <i class="ti ti-home"></i>
                    </button>
                </a>
                <button class="bg-sky-500 text-white text-2xl px-2 py-1 rounded-l hover:bg-sky-600 flex items-center">
                    <i class="ti ti-id"></i>
                </button>
                <span class="text-indigo-900">[ADMIN - PERFIL] {{ isset($profile) ? 'Editar' : 'Novo' }} </span>
            </div>
        </header>

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto">
                    <span class="text-black text-2xl">Dados do novo perfil</span>
                </div>
                <form action="{{ isset($profile) ? route('profile.update', $profile->id) : route('profile.store') }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (isset($profile))
                        @method('PUT')
                    @endif

                    <div>
                        <label for="entidade" class="block text-sm font-medium text-gray-700 mb-1">Entidade
                            Pública</label>
                        <x-select-tw :options="$entidades" :selected="isset($profile) ? $profile->entidade_id : null" class="select2 text-dark" required name="entidade_id"
                            :selected="old('entidade_id', isset($profile) ? $profile->entidade_id : null)" autocomplete required />
                        <x-input-error class="mt-2" :messages="$errors->get('entidade_id')" />
                    </div>

                    <div>
                        <label for="name" class="block font-medium text-black">Nome <span
                                class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="text" name="name" placeholder="alguma coisa"
                            title="Nome do perfil do usuário" :value="isset($profile) ? $profile->name : null" />
                    </div>

                    <div>
                        <label for="descricao" class="block font-medium text-black">Descrição <span
                                class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="text" name="descricao" placeholder="alguma coisa"
                            title="Descrição do perfil do usuário" :value="isset($profile) ? $profile->descricao : null" />
                    </div>

                    <div>
                        <label for="expiracaoAdm" class="block font-medium text-black">Expiração do Administrador <span
                                class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="number" name="expiracao_adm" placeholder="90"
                            title="Tempo para a expiração da senha do administrador parceiro" :value="isset($profile) ? $profile->expiracao_adm : null" />
                    </div>

                    <div>
                        <label for="expiracaoUser" class="block font-medium text-black">Expiração do Usuário <span
                                class="text-red-500">*</span></label></label></label>
                        <x-input-tw type="number" name="expiracao_user" placeholder="30"
                            title="Tempo para a expiração da senha do usuário parceiro" :value="isset($profile) ? $profile->expiracao_user : null" />
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
