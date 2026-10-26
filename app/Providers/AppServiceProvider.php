<?php

namespace App\Providers;

use App\Models\Producto;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($vista) {
    $cantidad = 0;
    $usuario = request()->user();

    if ($usuario && $usuario->rol->nombre === 'Administrador') {
        $cantidad = Producto::stockBajo()->count();
    }

    $vista->with('alertasStock', $cantidad);
});
    }
}