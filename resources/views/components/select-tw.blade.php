<div class="flex flex-col gap-1 w-full">
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        class="border-1 border-gray-600 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-md px-2 py-1 {{ inputClass() }}" {{ $attributes }}
        title="{{ $title }}"
        {{ $attributes->merge(['required' => $required]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $label)
            {{-- <option value="{{ $value }}" @selected(old($name) == $value)> --}}
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
