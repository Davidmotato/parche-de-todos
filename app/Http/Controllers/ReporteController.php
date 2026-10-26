<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    // lee las fechas del filtro; si no vienen o son inválidas, usa los últimos 7 días
    private function rango(Request $request)
    {
        $hoy = now()->startOfDay();
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $desde = ($desde && Carbon::hasFormat($desde, 'Y-m-d'))
            ? Carbon::parse($desde)->startOfDay()
            : $hoy->copy()->subDays(6);

        $hasta = ($hasta && Carbon::hasFormat($hasta, 'Y-m-d'))
            ? Carbon::parse($hasta)->endOfDay()
            : $hoy->copy()->endOfDay();

        // si las pusieron al revés, las intercambiamos
        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()->endOfDay()];
        }

        return [$desde, $hasta];
    }

    public function ventas(Request $request)
    {
        [$desde, $hasta] = $this->rango($request);

        $filas = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.ventas_id', '=', 'ventas.id')
            ->join('productos', 'productos.id', '=', 'detalle_ventas.productos_id')
            ->whereBetween('ventas.fecha', [$desde, $hasta])
            ->groupBy(DB::raw('DATE(ventas.fecha)'))
            ->orderBy('dia')
            ->selectRaw('DATE(ventas.fecha) as dia,
                COUNT(DISTINCT ventas.id) as num_ventas,
                SUM(detalle_ventas.subtotal) as total_vendido,
                SUM(detalle_ventas.cantidad * productos.precio_compra) as costo')
            ->get()
            ->map(function ($fila) {
                $fila->dia = Carbon::parse($fila->dia);
                $fila->ganancia = $fila->total_vendido - $fila->costo;

                return $fila;
            });

        $totales = [
            'ventas' => $filas->sum('num_ventas'),
            'vendido' => $filas->sum('total_vendido'),
            'costo' => $filas->sum('costo'),
            'ganancia' => $filas->sum('ganancia'),
        ];

        return view('reportes.ventas', compact('filas', 'totales', 'desde', 'hasta'));
    }

    public function productos(Request $request)
    {
        [$desde, $hasta] = $this->rango($request);

        $productos = DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.ventas_id')
            ->join('productos', 'productos.id', '=', 'detalle_ventas.productos_id')
            ->whereBetween('ventas.fecha', [$desde, $hasta])
            ->groupBy('productos.id', 'productos.nombre')
            ->selectRaw('productos.nombre,
                SUM(detalle_ventas.cantidad) as unidades,
                SUM(detalle_ventas.subtotal) as total_vendido')
            ->orderByDesc('unidades')
            ->limit(10)
            ->get();

        return view('reportes.productos', compact('productos', 'desde', 'hasta'));
    }

    public function movimientos(Request $request)
    {
        [$desde, $hasta] = $this->rango($request);
        $productoId = $request->query('producto');

        $entradas = DB::table('detalle_entradas')
            ->join('entradas', 'entradas.id', '=', 'detalle_entradas.entradas_id')
            ->join('productos', 'productos.id', '=', 'detalle_entradas.productos_id')
            ->join('usuarios', 'usuarios.id', '=', 'entradas.usuarios_id')
            ->whereBetween('entradas.fecha', [$desde, $hasta])
            ->when($productoId, function ($consulta) use ($productoId) {
                $consulta->where('productos.id', $productoId);
            })
            ->selectRaw("entradas.fecha, 'Entrada' as tipo, productos.nombre as producto,
                detalle_entradas.cantidad, usuarios.nombre as usuario, entradas.id as documento")
            ->get();

        $ventas = DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.ventas_id')
            ->join('productos', 'productos.id', '=', 'detalle_ventas.productos_id')
            ->join('usuarios', 'usuarios.id', '=', 'ventas.usuarios_id')
            ->whereBetween('ventas.fecha', [$desde, $hasta])
            ->when($productoId, function ($consulta) use ($productoId) {
                $consulta->where('productos.id', $productoId);
            })
            ->selectRaw("ventas.fecha, 'Venta' as tipo, productos.nombre as producto,
                detalle_ventas.cantidad, usuarios.nombre as usuario, ventas.id as documento")
            ->get();

        $movimientos = $entradas->concat($ventas)
            ->sortByDesc('fecha')
            ->values()
            ->take(300)
            ->map(function ($movimiento) {
                $movimiento->fecha = Carbon::parse($movimiento->fecha);

                return $movimiento;
            });

        $listaProductos = Producto::orderBy('nombre')->get(['id', 'nombre']);

        return view('reportes.movimientos', compact('movimientos', 'listaProductos', 'productoId', 'desde', 'hasta'));
    }
}