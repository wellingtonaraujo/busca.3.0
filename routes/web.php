<?php

use App\Http\Controllers\Adm\AclController;
use App\Http\Controllers\Adm\EmpresaController;
use App\Http\Controllers\Adm\ProfileRouteController;
use App\Http\Controllers\Audit\AuditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\Pessoa\PessoaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthorizedAccessRoute;
use App\Models\Custodiado\PessoaAntiga;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Auth::logout();
// rota padrão
// Route::get('/', [MainController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

// rota para o dashboard do sistema carceris
// Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Auth::routes();

Route::get('/force-500', function () {
    throw new \Exception('Erro 500 forçado para testes');
});

Route::get('/force404', function () {
    abort(404);
});

Route::get('/force-404-model', function () {
    User::findOrFail(99999999); // id que não existe
});

// routes/web.php
Route::get('/sftp-test', function () {
    try {
        $files = Storage::disk('sftp')->files('gsip/images/custodiados');
        $sample = $files[0] ?? null;
        if (!$sample) return 'SEM ARQUIVOS';
        $content = Storage::disk('sftp')->get($sample);
        return 'OK: ' . substr($content, 0, 50);
    } catch (\Throwable $e) {
        return 'ERRO SFTP: ' . $e->getMessage();
    }
});

Route::get('/test-image', function () {
    $p = public_path('assets/images/icons/avatar5.png');
    if (!is_file($p)) return 'ARQUIVO NÃO ENCONTRADO: ' . $p;

    $mime = mime_content_type($p);                 // ex.: image/png
    $b64  = base64_encode(file_get_contents($p));  // bytes → base64
    return 'data:' . $mime . ';base64,' . $b64;          // DATA-URI
});

// routes/web.php
use Illuminate\Support\Facades\DB;

Route::get('/test-foto/{idinterno}', function (int $idinterno) {
    // 1) mapear idinterno -> idpessoa no banco antigo
    $row = DB::connection('siapen')
        ->table('interno')
        ->select('idpessoa')
        ->where('idinterno', $idinterno)
        ->first();

    if (!$row) {
        return "interno {$idinterno} não encontrado na conexão 'siapen'.";
    }

    // 2) carregar o modelo que tem o método fotoSsh()
    $alvo = PessoaAntiga::where('id', $row->idpessoa)->first();
    if (!$alvo) {
        // se não existir na base nova, instancie só com o atributo necessário
        $alvo = new PessoaAntiga();
        $alvo->idpessoa = $row->idpessoa;
    }

    // 3) chamar o método
    $uri = $alvo->fotoSsh();

    // 4) render simples pra visualizar
    return <<<HTML
        <div style="font:14px/1.4 sans-serif">
            <p><strong>idinterno:</strong> {$idinterno}</p>
            <p><strong>idpessoa:</strong> {$row->idpessoa}</p>
            <p><strong>preview:</strong></p>
            <img src="{$uri}" alt="foto" style="max-width:280px;border:1px solid #ccc;padding:4px;border-radius:8px">
            <p><strong>prefixo data-uri:</strong> <code>{substr($uri, 0, 40)}...</code></p>
        </div>
    HTML;
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::get('/', [SearchController::class, 'index'])->middleware(['auth', 'verified'])->name('search');
    Route::middleware([AuthorizedAccessRoute::class])->group(function () {
        Route::resource('menu', MenuController::class, ['index']);
        Route::resource('modelo', ModeloController::class, ['index']);
        Route::resource('profile', ProfileController::class, ['index']);
        Route::resource('profileRoute', ProfileRouteController::class, ['index']);
        Route::resource('acl', AclController::class, ['index']);
        Route::resource('pessoa', PessoaController::class, ['index']);
        Route::resource('user', UserController::class, ['index']);
        Route::get('/pessoas/{id}/cpf', [PessoaController::class, 'getCPF'])->name('pessoas.getCPF');
        Route::get('/pessoas/{pessoa}/usuario', [PessoaController::class, 'usuario'])->name('pessoas.usuario');
        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
        Route::post('consultaPrisional', [SearchController::class, 'consultaPrisional'])->name('consultaPrisional');
    });
});
