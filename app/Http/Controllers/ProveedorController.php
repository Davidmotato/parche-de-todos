<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    private function reglas()
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:200'],
        ];
    }

    private function mensajes()
    {
        return [
            'nombre.required' => 'Escribe el nombre del proveedor.',
            'nombre.max' => 'El nombre no puede pasar de 150 caracteres.',
            'telefono.max' => 'El teléfono no puede pasar de 30 caracteres.',
            'direccion.max' => 'La dirección no puede pasar de 200 caracteres.',
        ];
    }

    public function index()
    {
        $proveedores = Proveedor::withCount('entradas')->orderBy('nombre')->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());

        Proveedor::create($datos);

        return redirect()->route('proveedores.index')
            ->with('exito', 'Proveedor creado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.editar', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $datos = $request->validate($this->reglas(), $this->mensajes());

        $proveedor->update($datos);

        return redirect()->route('proveedores.index')
            ->with('exito', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->entradas()->exists()) {
            return redirect()->route('proveedores.index')
                ->with('error', 'No se puede eliminar: el proveedor tiene entradas registradas.');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('exito', 'Proveedor eliminado correctamente.');
    }
}