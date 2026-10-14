<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    private function reglas()
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'categorias_id' => ['required', 'exists:categorias,id'],
            'precio_compra' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'precio_venta' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
        ];
    }

    private function mensajes()
    {
        return [
            'nombre.required' => 'Escribe el nombre del producto.',
            'nombre.max' => 'El nombre no puede pasar de 150 caracteres.',
            'categorias_id.required' => 'Selecciona una categoría.',
            'categorias_id.exists' => 'La categoría seleccionada no existe.',
            'precio_compra.required' => 'Escribe el precio de compra.',
            'precio_compra.numeric' => 'El precio de compra debe ser un número.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'precio_compra.max' => 'El precio de compra es demasiado alto.',
            'precio_venta.required' => 'Escribe el precio de venta.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'precio_venta.min' => 'El precio de venta no puede ser negativo.',
            'precio_venta.max' => 'El precio de venta es demasiado alto.',
            'stock.required' => 'Escribe el stock actual.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'stock_minimo.required' => 'Escribe el stock mínimo.',
            'stock_minimo.integer' => 'El stock mínimo debe ser un número entero.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
        ];
    }

    public function index(Request $request)
    {
        $busqueda = $request->query('buscar');
        $esVendedor = $request->user()->rol->nombre === 'Vendedor';

        $productos = Producto::with('categoria')
            ->when($busqueda, function ($consulta) use ($busqueda) {
                $consulta->where('nombre', 'like', '%' . $busqueda . '%');
            })
            // el vendedor solo ve los productos activos
            ->when($esVendedor, function ($consulta) {
                $consulta->where('estado', 'activo');
            })
            ->orderBy('nombre')
            ->get();

        return view('productos.index', compact('productos', 'busqueda'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.crear', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());
        $datos['estado'] = 'activo';

        Producto::create($datos);

        return redirect()->route('productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.editar', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());

        $producto->update($datos);

        return redirect()->route('productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    public function cambiarEstado(Producto $producto)
    {
        $producto->estado = $producto->estado === 'activo' ? 'inactivo' : 'activo';
        $producto->save();

        $mensaje = $producto->estado === 'activo'
            ? 'Producto activado.'
            : 'Producto desactivado.';

        return redirect()->route('productos.index')->with('exito', $mensaje);
    }
}
