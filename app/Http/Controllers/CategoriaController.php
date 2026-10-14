<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    private function mensajes()
    {
        return [
            'nombre.required' => 'Escribe el nombre de la categoría.',
            'nombre.max' => 'El nombre no puede pasar de 100 caracteres.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ];
    }

    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.crear');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre'],
        ], $this->mensajes());

        Categoria::create($datos);

        return redirect()->route('categorias.index')
            ->with('exito', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.editar', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,' . $categoria->id],
        ], $this->mensajes());

        $categoria->update($datos);

        return redirect()->route('categorias.index')
            ->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar: la categoría tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('exito', 'Categoría eliminada correctamente.');
    }
}