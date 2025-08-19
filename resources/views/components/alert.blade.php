@switch($type)
    @case('primary')
        <div class="bg-blue-700 border  text-sm text-blue-400 rounded-xl p-4 w-full" role="alert">
            <i class="ti ti-info-circle"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('secondary')
        <div class="bg-gray-800 border text-sm text-gray-400 rounded-xl p-4" role="alert">
            <i class="ti ti-moon-stars"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('success')
        <div class="bg-lime-300 border text-sm text-lime-900 rounded-xl p-4" role="alert">
            <i class="ti ti-circle-check"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('warning')
        <div class="bg-yellow-200 border text-sm text-yellow-900 rounded-xl p-4" role="alert">
            <i class="ti ti-alert-triangle"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('danger')
        <div class="bg-red-400 border text-sm text-red-900 rounded-xl p-4" role="alert">
            <i class="ti ti-circle-x"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('info')
        <div class="bg-cyan-400 border text-sm text-blue-800 rounded-xl p-4" role="alert">
            <i class="ti ti-info-square"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('light')
        <div class="bg-neutral-400 border border-gray-100 text-sm text-neutral-800 rounded-xl p-4" role="alert">
            <i class="ti ti-bulb"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @case('dark')
        <div class="bg-gray-100 border border-gray-400 text-sm text-gray-900 rounded-xl p-4" role="alert">
            <i class="ti ti-moon"></i>
            <span class="font-bold">{{ $title }}</span> {{ $message }}
        </div>
    @break

    @default
@endswitch
