@extends('layouts.errors')

@section('title', 'Página não encontrada')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-2xl p-8 text-center max-w-lg">
        <h1 class="text-6xl font-bold text-red-500 mb-4">404</h1>
        <h2 class="text-2xl font-semibold mb-2">Página não encontrada</h2>
        <p class="text-gray-600 mb-6">
            Opa! A página que você está tentando acessar não existe ou foi removida.
        </p>
        <a href="{{ route('home') }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
           Voltar para a página inicial
        </a>
    </div>
</div>
@endsection
