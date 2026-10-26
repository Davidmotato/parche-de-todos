<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class AlertaController extends Controller
{
    public function index()
    {
        $productos = Producto::stockBajo()
        ->with('categoria')
        ->orderBy('stock')
        ->orderBy('nombre')
        ->get();

        return view('alertas.index', compact('productos'));
    }
}
