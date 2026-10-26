<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index(Request $request)
    {
        $productosAlerta = collect();
        $totalAlertas = 0;

        if ($request->user()->rol->nombre === 'Administrador') {
            $totalAlertas = Producto::stockBajo()->count();

            $productosAlerta = Producto::stockBajo()
                ->orderBy('stock')
                ->orderBy('nombre')
                ->limit(5)
                ->get();
        }

        return view('inicio', compact('productosAlerta', 'totalAlertas'));
    }
}