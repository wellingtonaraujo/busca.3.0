@extends('layouts.errors')
@section('title', 'Erro interno')

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center text-center p-6">
        <h1 style="font-size:32px;margin:0 0 12px;display:flex;gap:12px;align-items:center;justify-content:center">
            {{-- Ícone banco de dados (SVG inline, leve e sem dependências) --}}
            <i class="ti ti-alert-triangle text-7xl text-red-500"></i>
        </h1>
        <h1 class="text-4xl font-bold mb-4">OPS! ALGO DEU ERRADO.</h1>
        <p class="mb-6">
            Não foi possível concluir sua solicitação no momento.
            Tente novamente em instantes. Se o problema persistir, contate o suporte.
        </p>
        <a href="{{ url('/') }}" class="px-4 py-2 rounded bg-cyan-600 hover:bg-cyan-700 text-white">
            Voltar para a página inicial
        </a>
    </div>
@endsection
