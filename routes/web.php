<?php

use App\Http\Controllers\Admin\RutaAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LugarVisitadoController;
use App\Http\Controllers\RecorridoController;
use App\Http\Controllers\RutaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/ingresar', [LoginController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/crear-cuenta', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/crear-cuenta', [RegisterController::class, 'register']);
});

Route::post('/salir', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::get('/rutas/{id}', [RutaController::class, 'show'])->name('rutas.show');

Route::get('/destinos', [DestinoController::class, 'index'])->name('destinos.index');
Route::get('/destinos/{id}', [DestinoController::class, 'show'])->name('destinos.show');

Route::get('/planificar', [RecorridoController::class, 'planificar'])->name('recorridos.planificar');

Route::middleware('auth')->group(function () {
    Route::post('/planificar/guardar', [RecorridoController::class, 'guardar'])->name('recorridos.guardar');
    Route::get('/mi-ruta', [RecorridoController::class, 'miRuta'])->name('recorridos.miRuta');
    Route::delete('/mi-ruta/{recorrido}', [RecorridoController::class, 'destroy'])->name('recorridos.destroy');
});

Route::middleware('auth')->prefix('perfil')->name('perfil.')->group(function () {
    Route::get('/progreso', [LugarVisitadoController::class, 'index'])->name('progreso');
    Route::post('/visitar', [LugarVisitadoController::class, 'store'])->name('visitar');
    Route::delete('/visitar/{destinoId}', [LugarVisitadoController::class, 'destroy'])->name('desmarcar');
});

Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('rutas', RutaAdminController::class);
});

