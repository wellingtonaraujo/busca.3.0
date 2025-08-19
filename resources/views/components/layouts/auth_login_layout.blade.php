<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/policia_penal.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    {{-- carregamento do vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ config('app.name') }}</title>
</head>

<body class="bg-gray-200">

    {{-- Incluindo a barra de topo --}}
    <x-layouts.top_bar />

    <div class="flex gap-6 p-6" id="main-wrapper">
        {{-- conteúdo dinamico --}}
        <div class="">
            {{ $slot }}
        </div>
    </div>

    {{-- carregamento de scripts --}}
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/iconify-icon/dist/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@preline/dropdown/index.js') }}"></script>
    <script src="{{ asset('assets/libs/@preline/overlay/index.js') }}"></script>

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    {{-- <script src="{{ asset('assets/libs/preline/dist/preline.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script> --}}

    <script src="{{ asset('assets/js/main_menu.js') }}"></script>
</body>

</html>
