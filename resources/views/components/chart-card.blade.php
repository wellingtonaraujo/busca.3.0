@props([
    'title' => 'Título',
    'subtitle' => '',
    'icon' => 'ti ti-chart-pie',
    'iconBg' => 'bg-blue-500',
    'bgColor' => 'bg-white',
    'lgColor' => 'text-gray-500',
    'txColor' => 'text-gray-400',
    'chartId' => 'chart-' . \Illuminate\Support\Str::random(5),
    'chartType' => 'line', // 'line' ou 'bar'
    'chartData' => [10, 20, 30],
    'chartLabels' => ['A', 'B', 'C'],
    'chartColor' => 'rgba(59, 130, 246, 0.7)',
])

<div class="w-full {{ $bgColor }} border border-gray-300 shadow-xl rounded-xl p-5 flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="{{ $lgColor }} font-semibold">{{ $title }}</h4>
            @if ($subtitle)
                <p class="text-sm {{ $txColor }}">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="p-3 rounded-full {{ $iconBg }} text-white text-3xl">
            <i class="{{ $icon }}"></i>
        </div>
    </div>

    <div>
        <canvas id="{{ $chartId }}" height="100"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
        const colors = @json($chartColor);

        new Chart(ctx, {
            type: '{{ $chartType }}',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    backgroundColor: Array.isArray(colors) ? colors : Array(
                        @json($chartData).length).fill(colors),
                    borderColor: Array.isArray(colors) ? colors : Array(
                        @json($chartData).length).fill(colors),
                    fill: '{{ $chartType === 'line' ? true : false }}',
                    tension: 0.4,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    });
</script>
