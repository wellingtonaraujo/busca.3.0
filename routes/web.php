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
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
