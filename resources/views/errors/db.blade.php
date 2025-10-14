@extends('layouts.errors_static')
@section('title', 'Falha na conexão com a base de dados')

@section('content')

    <div class="min-h-screen flex flex-col items-center justify-center text-center p-6">
        <h1 style="font-size:32px;margin:0 0 12px;display:flex;gap:12px;align-items:center;justify-content:center">
            {{-- Ícone banco de dados (SVG inline, leve e sem dependências) --}}
            <img src="{{ asset('assets/images/logos/database-error.png') }}" width="100" alt="logo-wrappixel" />
        </h1>
        <h1 class="text-4xl font-bold mb-4">FALHA NA CONEXÃO COM A BASE DE DADOS</h1>
        <p class="mb-6">
            Não conseguimos conectar ao banco no momento. Isso pode ser instabilidade ou manutenção.
            Por favor, tente novamente em alguns minutos.
        </p>

        @if (app()->environment('local'))
            {{-- Em local, opcionalmente mostre um hint para o dev --}}
            <strong class=" text-yellow-500 text-2xl">
                <i class="ti ti-alert-triangle"></i>
                Possíveis causas
            </strong>
            <pre class="text-left bg-gray-900 text-gray-200 p-4 rounded max-w-2xl overflow-auto">
            * Sem conexão de rede (Intranet / Internet);
            * Servidor de banco de dados fora do ar;
            * Sistema em manutenção;
            * Erro de Login na base de dados.
                    </pre>
        @endif

        <p class="mb-6">
            Aguarde alguns instantes e tente novamente, caso o erro persista entre em contato com o administrador do
            sistema.
        </p>

        <a href="{{ url('/') }}" class="px-4 py-2 rounded bg-cyan-600 hover:bg-cyan-700 text-white">
            Voltar para a página inicial
        </a>
    </div>
@endsection
