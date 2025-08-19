<?php

use App\Http\Controllers\Adm\AclController;
use App\Http\Controllers\Adm\EmpresaController;
use App\Http\Controllers\Adm\ProfileRouteController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\ProfileController;
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
Route::get('/', [MainController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::middleware([AuthorizedAccessRoute::class])->group(function () {
        Route::resource('menu', MenuController::class, ['index']);
        Route::resource('modelo', ModeloController::class, ['index']);
        Route::resource('profile', ProfileController::class, ['index']);
        Route::resource('profileRoute', ProfileRouteController::class, ['index']);
        Route::resource('acl', AclController::class, ['index']);
    });
});
