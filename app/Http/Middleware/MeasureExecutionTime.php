<?php

namespace App\Http\Middleware;

use Closure;

class MeasureExecutionTime
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        $executionTime = number_format($end - $start, 4);

        // Opcional: adiciona no header da resposta
        $response->headers->set('X-Execution-Time', "{$executionTime}s");

        // Opcional: loga no Laravel.log
        \Log::info("Tempo total da requisição: {$executionTime}s para " . $request->path());

        return $response;
    }
}

