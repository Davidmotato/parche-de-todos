<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    private function mensajes()
    {
        return [
            'lineas.required' => 'Agrega al menos un producto a la venta.',
            'lineas.min' => 'Agrega al menos un producto a la venta.',
            'lineas.*.productos_id.required' => 'Selecciona el producto en todas las filas.',
            'lineas.*.productos_id.distinct' => 'No repitas el mismo producto en dos filas.',
            'lineas.*.productos_id.exists' => 'Uno de los productos no existe o está desactivado.',
            'lineas.*.cantidad.required' => 'Escribe la cantidad en todas las filas.',
            'lineas.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'lineas.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'lineas.*.cantidad.max' => 'La cantidad es demasiado alta.',
        ];
    }

    public function index(Request $request)
    {
        $fecha = $request->query('fecha');

        if (! $fecha || ! Carbon::hasFormat($fecha, 'Y-m-d')) {
            $fecha = now()->toDateString();
        }

        $consulta = Venta::with('usuario')
            ->whereDate('fecha', $fecha)
            ->orderByDesc('fecha');

        // el vendedor solo ve sus propias ventas
        if ($request->user()->rol->nombre === 'Vendedor') {
            $consulta->where('usuarios_id', $request->user()->id);
        }

        $ventas = $consulta->get();
        $totalDia = $ventas->sum('total');

        return view('ventas.index', compact('ventas', 'fecha', 'totalDia'));
    }

    public function create()
    {
        $productos = Producto::where('estado', 'activo')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        return view('ventas.crear', compact('productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'lineas' => ['required', 'array', 'min:1'],
            'lineas.*.productos_id' => [
                'required',
                'distinct',
                Rule::exists('productos', 'id')->where('estado', 'activo'),
            ],
            'lineas.*.cantidad' => ['required', 'integer', 'min:1', 'max:100000'],
        ], $this->mensajes());

        $venta = DB::transaction(function () use ($datos, $request) {
            $ids = collect($datos['lineas'])->pluck('productos_id');

            // bloqueamos esos productos mientras dura la venta
            $productos = Producto::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

            $total = 0;
            $detalles = [];

            foreach ($datos['lineas'] as $linea) {
                $producto = $productos[$linea['productos_id']];
                $cantidad = (int) $linea['cantidad'];

                if ($cantidad > $producto->stock) {
                    throw ValidationException::withMessages([
                        'lineas' => 'Stock insuficiente de "' . $producto->nombre . '": quedan ' . $producto->stock . ' unidades.',
                    ]);
                }

                // el precio sale de la base de datos, no del formulario
                $subtotal = round($cantidad * $producto->precio_venta, 2);
                $total += $subtotal;

                $detalles[] = [
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio_venta,
                    'subtotal' => $subtotal,
                    'productos_id' => $producto->id,
                ];
            }

            $total = round($total, 2);

            if ($total > 99999999.99) {
                throw ValidationException::withMessages([
                    'lineas' => 'El total de la venta supera el valor máximo permitido.',
                ]);
            }

            $venta = Venta::create([
                'fecha' => now(),
                'total' => $total,
                'usuarios_id' => $request->user()->id,
            ]);

            foreach ($detalles as $detalle) {
                $venta->detalles()->create($detalle);

                $productos[$detalle['productos_id']]->decrement('stock', $detalle['cantidad']);
            }

            return $venta;
        });

        return redirect()->route('ventas.show', $venta)
            ->with('exito', 'Venta registrada correctamente.');
    }

    public function show(Request $request, Venta $venta)
    {
        if ($request->user()->rol->nombre === 'Vendedor' && $venta->usuarios_id != $request->user()->id) {
            abort(403, 'No tienes permiso para ver esta venta.');
        }

        $venta->load(['usuario', 'detalles.producto']);

        return view('ventas.ver', compact('venta'));
    }
}