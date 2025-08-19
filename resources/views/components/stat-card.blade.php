@props([
    'title' => 'Título',
    'icon' => 'ti ti-chart-pie',
    'iconBg' => 'bg-blue-500',
    "iconBorderColor" => 'bg-white',
    'number' => "123",
    'percent' => "12%",
    "description" => "Descrição do card",
])
<div class="max-w-xs bg-white border border-gray-400 shadow-2xl rounded-xl p-5 flex justify-between items-start">
    <div>
        <p class="text-xs uppercase text-gray-400 font-semibold">{{ $title }}</p>
        <h2 class="text-2xl font-bold text-gray-700">{{ $number }}</h2>
        <p class="text-sm text-green-600 mt-2">
            <span class="font-semibold">{{ $percent }}</span>
            <span class="text-gray-500"> {{ $description }}</span>
        </p>
    </div>
    <div
        class="w-12 h-12 {{ $iconBg }} border {{ $iconBorderColor }} rounded-full flex items-center justify-center shadow-xl">
        <i class="{{ $icon }} text-center text-xl"></i>
    </div>

</div>
