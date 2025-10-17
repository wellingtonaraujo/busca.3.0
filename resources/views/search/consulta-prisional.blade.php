<x-layouts.auth_layout_basic>
    @php
        $navigaion = false;
    @endphp
    <div class="">
        @if (isset($titulo))
            <x-page-header title="{{ $titulo }}" :breadcrumbs="$breadcrumbs" :buttons="$otherButtons" />
        @endif

        <div
            class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
            <div class="body w-full">
                <!-- Tabela Flex -->
                <div class="w-full overflow-x-auto">
                    Custodiado: <strong>{{ $custodiado->pessoa->nome }}</strong> (#{{ $custodiado->id }})
                </div>
            </div>
        </div>

        @php
            $fotoSrc = $foto ?? (optional($custodiado->pessoa)->foto ?? asset('assets/images/icons/no_image.png'));
        @endphp

        <div
            class="bg-gray-900 text-gray-200 p-6 rounded-xl shadow-md mx-auto border border-cyan-500 hover:shadow-cyan-500/50 mt-6">
            <div class="grid gap-4 md:grid-cols-[220px,1fr] lg:grid-cols-[256px,1fr] items-start">
                <!-- Coluna Foto (maior, 3x4 garantido) -->
                <div class="w-[220px] lg:w-[256px]">
                    <div class="aspect-[3/4] overflow-hidden rounded-md ring-1 ring-cyan-500">
                        <img src="{{ $fotoSrc }}" alt="Foto 3x4" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Coluna Dados -->
                <div class="space-y-3 text-sm ">
                    <dl class="grid grid-cols-[120px,1fr] gap-y-1 gap-x-2">
                        <dt class="text-gray-400">NOME:</dt>
                        <dd class="truncate">{{ $custodiado->pessoa->nome ?? '—' }}</dd>

                        <dt class="text-gray-400">DOCUMENTO:</dt>
                        <dd class="truncate">{{ $custodiado->pessoa->alcunha ?? '—' }}</dd>

                        <dt class="text-gray-400">NASCIMENTO:</dt>
                        <dd class="truncate">{{ $custodiado->pessoa->nascimento ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    </x-layouts.auth_layout>
