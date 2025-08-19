<div class="flex flex-col gap-1 w-full">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}" class="{{ inputClass() }}"
        value="{{ old($name, $value ?? '') }}"
        title="{{ $title ?? '' }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if ($required) required @endif
    >

    @error($name)
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
