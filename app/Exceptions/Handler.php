<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use PDOException;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Só usa página amigável de DB quando APP_DEBUG=false
     */
    protected function useFriendlyDbPage(): bool
    {
        return !config('app.debug'); // true em produção, false em dev
    }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // ---- DB FAILURES (apenas quando for pra exibir página amigável) ----
        $this->renderable(function (PDOException $e, $request) {
            if (!$this->useFriendlyDbPage()) {
                Log::channel('dbguard')->info('Debug ON: ignorando friendly DB page (PDOException)');
                return null;
            }
            Log::channel('dbguard')->error('[renderable] PDOException', $this->pack($e, $request));
            if ($this->isDbConnFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (PDOException + conexão)');
                return $this->dbFailureResponse($request, 'pdo');
            }
            return null;
        });

        $this->renderable(function (QueryException $e, $request) {
            if (!$this->useFriendlyDbPage()) {
                Log::channel('dbguard')->info('Debug ON: ignorando friendly DB page (QueryException)');
                return null;
            }
            Log::channel('dbguard')->error('[renderable] QueryException', $this->pack($e, $request));
            if ($this->isDbConnFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (QueryException + conexão)');
                return $this->dbFailureResponse($request, 'query');
            }
            return null;
        });

        $this->renderable(function (ConnectionNotFoundException $e, $request) {
            if (!$this->useFriendlyDbPage()) {
                Log::channel('dbguard')->info('Debug ON: ignorando friendly DB page (ConnectionNotFoundException)');
                return null;
            }
            Log::channel('dbguard')->error('[renderable] ConnectionNotFoundException', $this->pack($e, $request));
            return $this->dbFailureResponse($request, 'conn-not-found');
        });

        $this->renderable(function (InvalidArgumentException $e, $request) {
            if (!$this->useFriendlyDbPage()) {
                Log::channel('dbguard')->info('Debug ON: ignorando friendly DB page (InvalidArgumentException)');
                return null;
            }
            Log::channel('dbguard')->error('[renderable] InvalidArgumentException', $this->pack($e, $request));
            if ($this->isDbConfigFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (InvalidArgumentException + config)');
                return $this->dbFailureResponse($request, 'invalid-arg');
            }
            return null;
        });
    }

    public function render($request, Throwable $e)
    {
        Log::channel('dbguard')->error('[render] catch-all', $this->pack($e, $request));

        // 404 (rota ou model não encontrada)
        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            Log::channel('dbguard')->error('→ decisão: 404 (custom)');

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Página não encontrada.'], 404);
            }

            try {
                if (view()->exists('errors.404')) {
                    return response()->view('errors.404', [], 404);
                }
            } catch (\Throwable $ve) {
                Log::channel('dbguard')->error('Falha ao renderizar errors.404: '.$ve->getMessage());
            }

            return $this->notFoundFallbackHtml();
        }

        // Fallbacks adicionais para DB — somente se friendly page estiver habilitada
        if ($this->useFriendlyDbPage()) {
            if ($e instanceof PDOException && $this->isDbConnFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (render fallback PDO)');
                return $this->dbFailureResponse($request, 'render-fb-pdo');
            }
            if ($e instanceof QueryException && $this->isDbConnFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (render fallback Query)');
                return $this->dbFailureResponse($request, 'render-fb-query');
            }
            if ($e instanceof ConnectionNotFoundException) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (render fallback conn-not-found)');
                return $this->dbFailureResponse($request, 'render-fb-conn-not-found');
            }
            if ($e instanceof InvalidArgumentException && $this->isDbConfigFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (render fallback invalid-arg)');
                return $this->dbFailureResponse($request, 'render-fb-invalid-arg');
            }
            if ($this->looksLikeAnyDbFailure($e)) {
                Log::channel('dbguard')->error('→ decisão: dbFailureResponse (última barreira)');
                return $this->dbFailureResponse($request, 'last-barrier');
            }
        } else {
            Log::channel('dbguard')->info('Debug ON: deixando exceção seguir para Whoops.');
        }

        Log::channel('dbguard')->error('→ decisão: parent::render (sem match DB)');
        return parent::render($request, $e);
    }

    // ----------------- utilitários de detecção -----------------

    protected function isDbConnFailure(Throwable $e): bool
    {
        $msg = $e->getMessage() ?? '';
        $needles = [
            'SQLSTATE[HY000] [2002]',
            'SQLSTATE[HY000] [1045]',
            'SQLSTATE[HY000] [1049]',
            'SQLSTATE[HY000] [2006]',
            'Connection refused',
            'No such file or directory',
            'Access denied for user',
            'could not find driver',
            'getaddrinfo failed',
            'Connection timed out',
        ];
        foreach ($needles as $n) {
            if (stripos($msg, $n) !== false) return true;
        }
        return $this->traceMentionsDatabase($e);
    }

    protected function isDbConfigFailure(Throwable $e): bool
    {
        $msg = $e->getMessage() ?? '';

        $en = (stripos($msg, 'Database [') !== false && stripos($msg, '] not configured') !== false);

        $lower = Str::lower($msg);
        $pt1 = (stripos($msg, 'Conexão de banco de dados [') !== false && Str::contains($lower, 'não configurad'));
        $pt2 = (stripos($msg, 'Conexão') !== false && Str::contains($lower, 'não configurad') && strpos($msg, '[') !== false && strpos($msg, ']') !== false);
        $pt3 = (Str::contains($lower, 'não configurad') && Str::contains($lower, ['database', 'banco de dados', 'conexão']));

        return $en || $pt1 || $pt2 || $pt3 || $this->traceMentionsDatabase($e);
    }

    protected function looksLikeAnyDbFailure(Throwable $e): bool
    {
        $cur = $e;
        while ($cur) {
            if ($cur instanceof PDOException || $cur instanceof QueryException || $cur instanceof ConnectionNotFoundException) {
                return true;
            }
            if ($this->isDbConnFailure($cur) || $this->isDbConfigFailure($cur)) {
                return true;
            }
            $msg = $cur->getMessage() ?? '';
            if (Str::contains(Str::lower($msg), [
                'database', 'banco de dados', 'conexão', 'connection', 'not configured', 'não configurad'
            ]) && $this->traceMentionsDatabase($cur)) {
                return true;
            }
            $cur = $cur->getPrevious();
        }
        return false;
    }

    protected function traceMentionsDatabase(Throwable $e): bool
    {
        foreach ($e->getTrace() as $frame) {
            $file = $frame['file'] ?? '';
            $class = $frame['class'] ?? '';
            if (
                Str::contains($file, 'Illuminate/Database') ||
                Str::contains($class, 'Illuminate\\Database') ||
                Str::contains($class, 'PDO') ||
                Str::contains($class, 'Doctrine\\DBAL') ||
                Str::contains($file, 'DatabaseManager.php')
            ) {
                return true;
            }
        }
        return false;
    }

    // ----------------- respostas -----------------

    protected function dbFailureResponse($request, string $reasonTag = 'db')
    {
        Log::channel('dbguard')->error(">> dbFailureResponse acionado ({$reasonTag})", [
            'expects_json' => $request->expectsJson(),
            'full_url' => $request->fullUrl(),
            'path' => $request->path(),
            'method' => $request->method(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Falha na conexão com a base de dados. Tente novamente mais tarde.'
            ], 503)->header('X-Debug-DbGuard', $reasonTag);
        }

        // se a view falhar/ não existir, responda HTML estático para não virar 500
        try {
            if (view()->exists('errors.db')) {
                return response()->view('errors.db', [], 503)
                    ->header('X-Debug-DbGuard', $reasonTag);
            }
        } catch (\Throwable $ve) {
            Log::channel('dbguard')->error('Falha ao renderizar errors.db: '.$ve->getMessage());
        }

        return response(
            '<!doctype html><meta charset="utf-8"><title>DB fora do ar</title>'.
            '<body style="font-family:sans-serif;text-align:center;padding:40px;background:#0b1220;color:#e5e7eb">'.
            '<h1>FALHA NA CONEXÃO COM A BASE DE DADOS</h1>'.
            '<p>Tente novamente em alguns minutos.</p>'.
            '<p><a href="'.e(url('/')).'" style="color:#67e8f9">Voltar</a></p>'.
            '</body>', 503
        )->header('X-Debug-DbGuard', $reasonTag);
    }

    protected function notFoundFallbackHtml()
    {
        $html = '<!doctype html><meta charset="utf-8"><title>404</title>'.
            '<body style="font-family:sans-serif;text-align:center;padding:40px;background:#0b1220;color:#e5e7eb">'.
            '<h1>404 — PÁGINA NÃO ENCONTRADA</h1>'.
            '<p>A rota solicitada não existe ou foi movida.</p>'.
            '<p><a href="'.e(url('/')).'" style="color:#67e8f9">Voltar para a página inicial</a></p>'.
            '</body>';
        return response($html, 404);
    }

    // ----------------- logging helpers -----------------

    protected function pack(Throwable $e, $request): array
    {
        return [
            'ex_class' => get_class($e),
            'ex_message' => $e->getMessage(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'method' => $request->method(),
            'previous_chain' => $this->chain($e),
        ];
    }

    protected function chain(Throwable $e): array
    {
        $out = [];
        $cur = $e;
        while ($cur) {
            $out[] = get_class($cur).': '.Str::limit($cur->getMessage() ?? '', 500);
            $cur = $cur->getPrevious();
        }
        return $out;
    }
}
