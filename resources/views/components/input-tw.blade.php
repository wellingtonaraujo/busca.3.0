<div class="flex flex-col gap-1 w-full">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        class="border-1 border-gray-600 focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 rounded-md px-2 py-1 {{ inputClass() }}"
        value="{{ old($name, $value ?? '') }}"
        title="{{ $title ?? '' }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if ($required) required @endif
    >

    @error($name)
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
