@props([
    'title' => 'Título', // Título principal do header
    'breadcrumbs' => [], // Array de breadcrumbs
    'buttons' => [], // Array de botões opcionais
])

<header class="bg-yellow-500 text-black shadow-md rounded-xl w-full text-sm py-4 px-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <!-- Título e breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center gap-2">
            <h1 class="text-2xl font-bold">{{ $title }}</h1>

            @if (!empty($breadcrumbs))
                <nav class="text-gray-700 text-sm md:ml-4" aria-label="Breadcrumb">
                    <ol class="list-reset flex space-x-2">
                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="flex items-center">
                                @if (isset($breadcrumb['link']))
                                    <a href="{{ $breadcrumb['link'] }}" class="text-gray-700 hover:text-gray-900">
                                        @if (isset($breadcrumb['icon']))
                                            <i class="{{ $breadcrumb['icon'] }}"></i>
                                        @endif
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-gray-500">
                                        @if (isset($breadcrumb['icon']))
                                            <i class="{{ $breadcrumb['icon'] }}"></i>
                                        @endif
                                        {{ $breadcrumb['label'] }}
                                    </span>
                                @endif
                                @if (!$loop->last)
                                    <span class="mx-2 text-gray-400">/</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>

        <!-- Botões em grupo -->
        @if(!empty($buttons))
            <div class="flex items-center gap-1">
                <div class="inline-flex shadow-md">
                    @foreach ($buttons as $index => $button)
                        @php
                            $first = $index === 0 ? 'rounded-l-lg' : '';
                            $last = $index === count($buttons) - 1 ? 'rounded-r-lg' : '';
                            $middle = (!$first && !$last) ? 'rounded-none' : '';
                        @endphp

                        @if(isset($button['link']))
                            {{ dd($button['bg']) }}
                            <a href="{{ $button['link'] }}">
                                <button
                                    class="{{ $button['bg'] }} {{ $button['text'] }} {{ $first }} {{ $middle }} {{ $last }} text-2xl px-2 py-1 hover:{{ $button['hover'] }} flex items-center border border-gray-300 -ml-px first:ml-0"
                                    title="{{ $button['title'] }}">
                                    <i class="{{ $button['icon'] }}"></i>
                                </button>
                            </a>
                        @else
                            <button
                                class="{{ $button['bg'] }} {{ $button['text'] }} {{ $first }} {{ $middle }} {{ $last }} text-2xl px-2 py-1 hover:{{ $button['hover'] }} flex items-center border border-gray-300 -ml-px first:ml-0"
                                title="{{ $button['title'] }}">
                                <i class="{{ $button['icon'] }}"></i>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</header>
    </div>
</header>
