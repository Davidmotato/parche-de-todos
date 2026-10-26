<?php

use App\Http\Controllers\VentaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('inicio') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/inicio', [InicioController::class, 'index'])->name('inicio')->middleware('auth');



Route::middleware(['auth', 'rol:Administrador,Vendedor'])->group(function () {
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');


    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
});

Route::middleware(['auth', 'rol:Administrador'])->group(function () {
    Route::resource('categorias', CategoriaController::class)->except('show');
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');

Route::get('/reportes', function (){
    return redirect()->route('reportes.ventas');
})->name('reportes.index');
Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
Route::get('/reportes/productos', [ReporteController::class, 'productos'])->name('reportes.productos');
Route::get('/reportes/movimientos', [ReporteController::class, 'movimientos'])->name('reportes.movimientos');
    

Route::resource('proveedores', ProveedorController::class)
    ->except('show')
    ->parameters(['proveedores' => 'proveedor']);
    
Route::resource('entradas', EntradaController::class)
->only(['index', 'create', 'store', 'show']);    

    Route::resource('productos', ProductoController::class)->except(['index', 'show', 'destroy']);
    Route::patch('/productos/{producto}/estado', [ProductoController::class, 'cambiarEstado'])->name('productos.estado');
});