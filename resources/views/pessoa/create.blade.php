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
                                <img id="preview-foto"
                                    src="data:image/jpeg;base64,{{ base64_encode($pessoa->foto_perfil) }}"
                                    alt="Foto de Perfil"
                                    class="w-40 h-40 rounded-full object-cover shadow-lg border-4 border-gray-200" />
                            @else
                                <img id="preview-foto" src="https://via.placeholder.com/160?text=Sem+Foto"
                                    alt="Foto de Perfil"
                                    class="w-40 h-40 rounded-full object-cover shadow-lg border-4 border-gray-200 bg-gray-100 text-gray-500" />
                            @endif

                            {{-- Botão de upload --}}
                            <label for="foto_perfil"
                                class="absolute bottom-2 right-2 cursor-pointer bg-green-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-green-700">
                                Alterar
                            </label>
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" class="hidden"
                                onchange="previewFoto(event)" required />
                        </div>
                        <span class="text-gray-700 font-medium">Foto de Perfil</span>
                    </div>

                    {{-- Coluna do formulário --}}
                    <div class="col-span-2 space-y-4">
                        <h2 class="text-2xl font-semibold mb-6 text-left">Formulário de Cadastro</h2>
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome da
                                pessoa</label>
                            <x-input-tw type="text" name="nome" :value="old('nome', isset($pessoa) ? Str::upper($pessoa->nome) : null)" autofocus
                                placeholder="João dos Santos Filho" title="Nome da pessoa - ex: Fulano de Tal"
                                required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- CPF -->
                            <div>
                                <x-input-label for="cpf" :value="__('Número do CPF (*)')" />
                                <x-input-tw id="cpf" name="cpf" type="text" class="mt-1 block w-full"
                                    :value="old('cpf', isset($pessoa) ? Str::upper($membro->cpf) : null)" required autocomplete="cpf"
                                    title="Campo requerido, número do CPF" />
                            </div>

                            <!-- Data de Nascimento -->
                            <div>
                                <x-input-label for="nascimento" :value="__('Data de Nascimento (*)')" />
                                <x-input-tw id="nascimento" name="nascimento" type="date" class="mt-1 block w-full"
                                    :value="old('nascimento', isset($pessoa) ? $pessoa->nascimento : null)" required autocomplete="bday"
                                    title="Campo requerido, data de nascimento" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <!-- Entidade -->
                            <div class="md:col-span-5">
                                <x-input-label for="entidade" :value="__('Órgão Público')" />
                                <x-select-tw :options="$entidadeOptions" :selected="isset($pessoa) ? $pessoa->sexo_id : null" class="text-dark" required
                                    name="sexo_id" :selected="old('sexo_id', isset($pessoa) ? $pessoa->sexo_id : null)" autocomplete="pessoa" required />
                                <x-input-error class="mt-2" :messages="$errors->get('sexo_id')" />
                            </div>

                            <!-- Matricula -->
                            <div class="md:col-span-1">
                                <x-input-label for="matricula" :value="__('Número da matrícula (*)')" />
                                <x-input-tw id="matricula" name="matricula" type="number" class="mt-1 block w-full"
                                    :value="old('matricula', isset($pessoa) ? $pessoa->matricula : null)" required autocomplete="bday"
                                    title="Campo requerido, número de matricula" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <!-- CEP -->
                            <div class="md:col-span-1">
                                <x-input-label for="cep" :value="__('Número do CEP (*)')" />
                                <x-input-tw id="cep" name="cep" type="text" class="mt-1 block w-full"
                                    :value="old('cep', isset($pessoa) ? Str::upper($membro->cep) : null)" required autocomplete="postal-code"
                                    title="Campo requerido, número do CEP" />
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="md:col-span-4">
                                <x-input-label for="logradouro" :value="__('Logradouro (*)')" />
                                <x-input-tw id="logradouro" name="logradouro" type="logradouro"
                                    class="mt-1 block w-full" :value="old('logradouro', isset($pessoa) ? $pessoa->nascimento : null)" required autocomplete="bday"
                                    title="Campo requerido, Rua, Avenida, Travesa" />
                            </div>

                            <!-- Número da casa -->
                            <div class="md:col-span-1">
                                <x-input-label for="numero" :value="__('Número (*)')" />
                                <x-input-tw id="numero" name="numero" type="text" class="mt-1 block w-full"
                                    :value="old('numero', isset($pessoa) ? $pessoa->numero : null)" required autocomplete="address-line2"
                                    title="Campo requerido, número da casa" />
                            </div>
                        </div>


                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                            <x-select-tw :options="$sexoOptions" :selected="isset($pessoa) ? $pessoa->sexo_id : null" class="text-dark" required
                                name="sexo_id" :selected="old('sexo_id', isset($pessoa) ? $pessoa->sexo_id : null)" autocomplete="pessoa" required />
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

    <script>
        function previewFoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('preview-foto');
                img.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("nome").focus();
        });
    </script>
</x-layouts.auth_layout>
