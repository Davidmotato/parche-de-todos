<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EntradaController extends Controller
{
    private function mensajes()
    {
        return [
            'proveedores_id.required' => 'Selecciona un proveedor.',
            'proveedores_id.exists' => 'El proveedor seleccionado no existe.',
            'fecha.required' => 'Indica la fecha de la entrada.',
            'fecha.date' => 'La fecha no es válida.',
            'fecha.before_or_equal' => 'La fecha no puede ser futura.',
            'lineas.required' => 'Agrega al menos un producto a la entrada.',
            'lineas.min' => 'Agrega al menos un producto a la entrada.',
            'lineas.*.productos_id.required' => 'Selecciona el producto en todas las filas.',
            'lineas.*.productos_id.distinct' => 'No repitas el mismo producto en dos filas.',
            'lineas.*.productos_id.exists' => 'Uno de los productos no existe o está desactivado.',
            'lineas.*.cantidad.required' => 'Escribe la cantidad en todas las filas.',
            'lineas.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'lineas.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'lineas.*.cantidad.max' => 'La cantidad es demasiado alta.',
            'lineas.*.precio_unitario.required' => 'Escribe el precio unitario en todas las filas.',
            'lineas.*.precio_unitario.numeric' => 'El precio unitario debe ser un número.',
            'lineas.*.precio_unitario.min' => 'El precio unitario no puede ser negativo.',
            'lineas.*.precio_unitario.max' => 'El precio unitario es demasiado alto.',
        ];
    }

    public function index()
    {
        $entradas = Entrada::with(['proveedor', 'usuario'])
            ->orderByDesc('fecha')
            ->get();

        return view('entradas.index', compact('entradas'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $productos = Producto::where('estado', 'activo')->orderBy('nombre')->get();

        return view('entradas.crear', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'proveedores_id' => ['required', 'exists:proveedores,id'],
            'fecha' => ['required', 'date', 'before_or_equal:now'],
            'lineas' => ['required', 'array', 'min:1'],
            'lineas.*.productos_id' => [
                'required',
                'distinct',
                Rule::exists('productos', 'id')->where('estado', 'activo'),
            ],
            'lineas.*.cantidad' => ['required', 'integer', 'min:1', 'max:100000'],
            'lineas.*.precio_unitario' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ], $this->mensajes());

        // el total lo calculamos aquí, no confiamos en el que muestra la pantalla
        $total = 0;
        foreach ($datos['lineas'] as $linea) {
            $total += $linea['cantidad'] * $linea['precio_unitario'];
        }
        $total = round($total, 2);

        if ($total > 99999999.99) {
            return back()->withInput()
                ->withErrors(['lineas' => 'El total de la entrada es demasiado alto.']);
        }

        DB::transaction(function () use ($datos, $total, $request) {
            $entrada = Entrada::create([
                'fecha' => $datos['fecha'],
                'total' => $total,
                'proveedores_id' => $datos['proveedores_id'],
                'usuarios_id' => $request->user()->id,
            ]);

            foreach ($datos['lineas'] as $linea) {
                $entrada->detalles()->create([
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'productos_id' => $linea['productos_id'],
                ]);

                Producto::where('id', $linea['productos_id'])
                    ->increment('stock', (int) $linea['cantidad']);
            }
        });

        return redirect()->route('entradas.index')
            ->with('exito', 'Entrada registrada y stock actualizado.');
    }

    public function show(Entrada $entrada)
    {
        $entrada->load(['proveedor', 'usuario', 'detalles.producto']);

        return view('entradas.ver', compact('entrada'));
    }
}