@props([
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ old($name, $value) }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500'
    ]) }}
>
