@props([
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'title' => '', // 👈 Adicione esta linha
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    title="{{ $title }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500'
    ]) }}
>
