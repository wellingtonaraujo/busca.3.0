@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' =>
            'border-2 border-gray-500 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600 rounded-md shadow-sm',
    ]) !!}>
