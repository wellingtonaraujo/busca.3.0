<x-layouts.auth_layout>
    <div class="">
        <header class="bg-white shadow-md rounded-xl w-full text-sm py-4 px-6">
            [TÍTULO DA PAGINA]
        </header>

        {{-- <i class="ti ti-moon text-xl text-4xl"></i> --}}
        {{-- <i class="ti ti-moon-filled text-xl text-4xl"></i> --}}
        {{-- <i class="ti ti-shopping-bag text-xl text-4xl"></i> --}}
        {{-- <i class="ti ti-chart-arrows text-xl text-4xl"></i> --}}
        {{-- <i class="ti ti-chart-arcs text-xl text-4xl"></i> --}}

        <div
            class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card grid grid-cols-1 lg:grid-cols-3 lg:gap-x-6 gap-x-0 lg:gap-y-0 gap-y-6">
            <x-alert type="warning" title="Atenção!" message="Mensagem padrão" />
            <x-alert type="danger" title="Erro!" message="Mensagem padrão" />
            <x-alert type="success" title="Sucesso!" message="Mensagem padrão" />
            <x-alert type="primary" title="Importante!" message="Mensagem padrão" />
            <x-alert type="secondary" title="Nota!" message="Mensagem padrão" />
            <x-alert type="light" title="Observação:" message="Mensagem padrão" />
            <x-alert type="dark" title="Obs. Importante:" message="Mensagem padrão" />
            <x-alert type="info" title="Informação" message="Mensagem padrão" />
        </div>
        <div
            class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card grid grid-cols-1 lg:grid-cols-4 lg:gap-x-6 gap-x-0 lg:gap-y-0 gap-y-6">
            <x-stat-card title="TITLE" number="321,15" percent="15,00%" description="Descrição dos dados"
                icon="ti ti-chart-pie text-white text-3xl" iconBg="bg-red-500" iconBorderColor="border-red-200" />

            <x-stat-card title="ECONOMIA" number="321,15" percent="15,00%" description="Economia no periodo"
                icon="ti ti-chart-arrows text-white text-3xl" iconBg="bg-lime-600" iconBorderColor="border-lime-200" />

            <x-stat-card title="ECONOMIA" number="321,15" percent="15,00%" description="Economia no periodo"
                icon="ti ti-chart-arrows text-white text-3xl" iconBg="bg-blue-500" iconBorderColor="border-blue-200" />

            <x-stat-card title="ECONOMIA" number="321,15" percent="15,00%" description="Economia no periodo"
                icon="ti ti-chart-arcs text-white text-3xl" iconBg="bg-black" iconBorderColor="border-gray-200" />
        </div>

        <div
            class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card grid grid-cols-1 lg:grid-cols-3 lg:gap-x-6 gap-x-0 lg:gap-y-0 gap-y-6">
            <x-chart-card
                title="BAR"
                subtitle="Gráfico tipo barra"
                icon="ti ti-car"
                iconBg="bg-amber-700"
                bgColor="bg-amber-400"
                lgColor="text-amber-900"
                txColor="text-amber-800"
                chart-type="bar"
                :chart-data="[12, 19, 7, 15, 10, 20, 25]"
                :chart-labels="['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />

            <x-chart-card
                title="RADAR"
                subtitle="Gráfico tipo radar"
                icon="ti ti-users text-white"
                iconBg="bg-blue-500"
                lgColor="text-black"
                txColor="text-gray-800"
                chart-type="radar"
                :chart-data="[50, 60, 55, 70, 65, 80, 90]"
                :chart-labels="['Dia 1', 'Dia 5', 'Dia 10', 'Dia 15', 'Dia 20', 'Dia 25', 'Dia 30']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />

            <x-chart-card
                title="DOUGHNUT"
                subtitle="Gráfico tipo rosquinha"
                icon="ti ti-moneybag bg text-black"
                iconBg="bg-yellow-300"
                bgColor="bg-black"
                lgColor="text-white"
                txColor="text-gray-300"
                chart-type="doughnut"
                :chart-data="[50.5, 60.15, 55.14, 70.25, 65.35, 80.95, 90]"
                :chart-labels="['Dia 1', 'Dia 5', 'Dia 10', 'Dia 15', 'Dia 20', 'Dia 25', 'Dia 30']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />
        </div>
        <div
            class="flex gap-6 p-6 mt-6 bg-white shadow-md rounded-xl card grid grid-cols-1 lg:grid-cols-3 lg:gap-x-6 gap-x-0 lg:gap-y-0 gap-y-6">
            <x-chart-card
                title="POLARAREA"
                subtitle="Grafico tipo área polar"
                icon="ti ti-shopping-bag"
                iconBg="bg-lime-700"
                bgColor="bg-lime-200"
                lgColor="text-lime-900"
                txColor="text-lime-800"
                chart-type="polarArea"
                :chart-data="[12, 19, 7, 15, 10, 20, 25]"
                :chart-labels="['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />

            <x-chart-card title="PIE" subtitle="Gráfico tipo pizza"
                icon="ti ti-users text-white"
                iconBg="bg-purple-700"
                bgColor="bg-purple-300"
                lgColor="text-purple-900"
                txColor="text-purple-800"
                chart-type="pie"
                :chart-data="[50, 60, 55, 70, 65, 80, 90]"
                :chart-labels="['Dia 1', 'Dia 5', 'Dia 10', 'Dia 15', 'Dia 20', 'Dia 25', 'Dia 30']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />

            <x-chart-card
                title="BAR"
                subtitle="Gráfico tipo barra"
                icon="ti ti-cash bg text-indigo-100"
                iconBg="bg-indigo-700"
                bgColor="bg-indigo-200"
                lgColor="text-indigo-900"
                txColor="text-indigo-800"
                chart-type="bar"
                :chart-data="[50.5, 60.15, 55.14, 70.25, 65.35, 80.95, 90]"
                :chart-labels="['Dia 1', 'Dia 5', 'Dia 10', 'Dia 15', 'Dia 20', 'Dia 25', 'Dia 30']"
                :chart-color="[
                    'rgba(59, 130, 246, 0.5)',
                    'rgba(16, 185, 129, 0.5)',
                    'rgba(234, 179, 8, 0.5)',
                    'rgba(244, 63, 94, 0.5)',
                    'rgba(168, 85, 247, 0.5)',
                    'rgba(249, 115, 22, 0.5)',
                    'rgba(14, 165, 233, 0.5)',
                ]" />
    </div>
    </div>
</x-layouts.auth_layout>
