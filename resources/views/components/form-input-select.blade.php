@props([
    'name',
    'options' => [], // array ['valor' => 'Texto']
    'placeholder' => null,
    'value' => '', // valor selecionado
])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500'
    ]) }}
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $key => $label)
        <option value="{{ $key }}" @selected(old($name, $value) == $key)>
            {{ $label }}
        </option>
    @endforeach
</select>
