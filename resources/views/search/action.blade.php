@php
    $cid = $custodiado_id ?? (optional($model->custodiado)->id ?? null);
    $origem = $model->origem;
@endphp

@if (!is_null($cid))
    <form method="POST" action="{{ route('consultaPrisional') }}">
        @csrf
        <input type="hidden" name="pessoa_id" value="{{ $model->id }}">
        <input type="hidden" name="custodiado_id" value="{{ $cid }}">
        <input type="hidden" name="origem" value="{{ $origem }}">
        <input type="hidden" name="routeSearch" value="{{ $routeSearch }}">
        <button type="submit" class="text-blue-500 hover:text-blue-700" title="Consulta Prisional">
            <i class="ti ti-search text-cyan-100 text-2xl"></i>
        </button>
    </form>
@endif

<a href="#" class="text-blue-500 hover:text-blue-700" title="Historico Prisional">
    <i class="ti ti-history-toggle text-cyan-100 text-2xl"></i>
</a>

<a href="#" class="text-blue-500 hover:text-blue-700" title="Relação de pessoas vinculadas">
    <i class="ti ti-user-square-rounded text-cyan-100 text-2xl"></i>
</a>

<a href="#" class="text-blue-500 hover:text-blue-700" title="Historico disciplinar">
    <i class="ti ti-file-cv text-cyan-100 text-2xl"></i>
</a>

<a href="#" class="text-blue-500 hover:text-blue-700" title="Imagens da pessoa">
    <i class="ti ti-camera text-cyan-100 text-2xl"></i>
</a>
