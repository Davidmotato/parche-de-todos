<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('inicio') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/inicio', function () {
    return view('inicio');
})->name('inicio')->middleware('auth');



Route::middleware(['auth', 'rol:Administrador,Vendedor'])->group(function () {
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
});

Route::middleware(['auth', 'rol:Administrador'])->group(function () {
    Route::resource('categorias', CategoriaController::class)->except('show');

    Route::resource('productos', ProductoController::class)->except(['index', 'show', 'destroy']);
    Route::patch('/productos/{producto}/estado', [ProductoController::class, 'cambiarEstado'])->name('productos.estado');
});