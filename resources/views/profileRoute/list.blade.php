<div class="flex bg-cyan-300 hover:bg-cyan-400 p-3 mb-4 text-1xl rounded-xl card ">
    <strong><i class="ti ti-info-circle text-3xl"></i> {{ $profile->name }} <i class="ti ti-arrow-right"></i> {{ $profile->descricao }}.</strong>
</div>
<div>
    <input type="checkbox" id="selecionar-todos">
    <label for="selecionar-todos">Selecionar Todas</label>
</div>
<form action="{{ route('profileRoute.store') }}" method="POST" class="mt-6 space-y-4">
    @csrf
    @foreach ($acls as $acl)
        @php
            $routes = $acl->getRoutes($acl->name);
        @endphp
        {!! Form::hidden('profile_id', $profile->id) !!}
        <div x-data="{ open: false }" class="relative w-full mb-2">
            <!-- Botão -->
            <button @click="open = !open" type="button"
                class="w-full bg-gray-200 text-black px-4 py-2 rounded flex items-center justify-start gap-2">

                <!-- Ícone à esquerda -->
                <i class="ti ti-chevron-down text-xl flex-shrink-0 transition-transform duration-200 text-black"
                    :class="{ 'rotate-180': open }"></i>

                <!-- Texto do botão -->
                <span class="flex items-center gap-2">
                    <strong>{{ $acl->name }}</strong> <i class="ti ti-arrow-right"></i> <span
                        class="text-yellow-600 font-bold">{{ $acl->descricao }}</span>
                </span>
            </button>

            <!-- Menu Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="absolute mt-1 w-full bg-white rounded shadow-lg z-50 overflow-hidden border border-gray-200">
                @foreach ($routes as $item)
                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer text-black text-sm">
                        <input type="checkbox" name="route[]" value="{{ $item->id }}" class="mr-2 checkbox-rota"
                            {{ $profileRoutes->where('route_id', $item->id)->first() ? 'checked' : '' }}>
                        {{ $item->name ?? 'Descrição vazia' }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="flex gap-2 pt-4">
        <!-- Botão de voltar -->
        <a href="{{ route('profile.index') }}">
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
