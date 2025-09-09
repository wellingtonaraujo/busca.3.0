<x-layouts.auth_layout>
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card">
            <div class="body w-full">
                <div class="w-full overflow-x-auto text-black">
                    <span class="text-2xl">Dados do usuário</span>
                </div>
                <form action="{{ !empty($user) ? route("$route.update", $user->id) : route("$route.store") }}"
                    method="POST" class="mt-6 space-y-4">
                    @csrf
                    @if (!empty($user))
                        @method('PUT')
                    @endif

                    {{-- Pessoa --}}
                    <div class="md:col-span-5">
                        <x-input-label for="pessoa" :value="__('Nome do usuário')" />
                        @if (isset($user))
                            <p class="text-black">CPF: <strong>{{ $user->pessoa->cpf }}</strong> : <strong>{{ $user->pessoa->nome }}</strong></p>
                            <p class="text-black">Entidade de Origem: <strong>({{ $user->pessoa->entidade_id }}) </strong> <strong>{{ $user->pessoa->entidade->nome }}</strong> : <strong>{{ $user->pessoa->entidade->sigla }}</strong></p>
                        @else
                            <x-select-tw :options="$pessoaOptions" :selected="isset($user) ? $user->pessoa_id : null" class="select2 text-dark" required
                                name="pessoa_id" :selected="old('pessoa_id', isset($user) ? $user->pessoa_id : null)" autocomplete="pessoa" required id="pessoa_id" />
                        @endif
                        <x-input-error class="mt-2" :messages="$errors->get('pessoa_id')" />
                    </div>

                    <!-- Entidade -->
                    <div class="md:col-span-5">
                        <x-input-label for="entidade" :value="__('Órgão Público')" />
                        <x-select-tw :options="$entidadeOptions" :selected="isset($user) ? $user->entidade_id : null" class="select2 text-dark" required
                            name="entidade_id" :selected="old('entidade_id', isset($user) ? $user->entidade_atual_id : null)" autocomplete="pessoa" />
                        <x-input-error class="mt-2" :messages="$errors->get('entidade_id')" />
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                        <x-input-tw type="text" name="departamento" :value="old('departamento', isset($user) ? Str::upper($user->departamento) : null)" placeholder="-- Nenhum --"
                            title="Nome do departamento onde trabalha" required />
                        <x-input-error class="mt-2" :messages="$errors->get('departamento')" />
                    </div>

                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-1">Função</label>
                        <x-input-tw type="text" name="funcao" :value="old('funcao', isset($user) ? Str::upper($user->funcao) : null)" placeholder="-- Nenhum --"
                            title="Nome do funcao onde trabalha" required />
                        <x-input-error class="mt-2" :messages="$errors->get('funcao')" />
                    </div>

                    <!-- Status do usuário -->
                    <div class="md:col-span-5">
                        <x-input-label for="user_status_id" :value="__('Status do usuário')" />
                        <x-select-tw :options="$statusOptions" :selected="isset($user) ? $user->user_status_id : null" class="select2 text-dark" required
                            name="user_status_id" :selected="old('user_status_id', isset($user) ? $user->user_status_id : null)" autocomplete="user_status_id" />
                        <x-input-error class="mt-2" :messages="$errors->get('user_status_id')" />
                    </div>

                    <!-- perfil do usuario -->
                    <div class="md:col-span-5">
                        <x-input-label for="profile" :value="__('Perfil do usuário')" />
                        <x-select-tw :options="$profileOptions" :selected="isset($user) ? $user->profile_id : null" class="select2 text-dark" required
                            name="profile_id" :selected="old('profile_id', isset($user) ? $user->profile_id : null)" autocomplete="profile_id" required />
                        <x-input-error class="mt-2" :messages="$errors->get('profile_id')" />

                        <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div class="flex gap-2 pt-4">
                        <!-- Botão de voltar -->
                        <a href="{{ route('user.index') }}">
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
