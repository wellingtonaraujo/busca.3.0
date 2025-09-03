<div class="flex flex-col gap-1 w-full">
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        class="{{ $attributes->get('class') ? $attributes->get('class') : '' }}
               border-1 border-gray-600 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-md px-2 py-1 {{ inputClass() }}"
        title="{{ $title }}"
        {{ $attributes->merge(['required' => $required]) }}>

        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
