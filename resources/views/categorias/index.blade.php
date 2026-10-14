@extends('layouts.app')

@section('titulo', 'Categorías')

@section('contenido')
    <div class="encabezado-pagina">
        <h2>Categorías</h2>
        <a href="{{ route('categorias.create') }}" class="btn btn-auto">Nueva categoría</a>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Productos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->productos_count }}</td>
                    <td class="celda-acciones">
                        <a href="{{ route('categorias.edit', $categoria) }}" class="enlace-accion">Editar</a>

                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar esta categoría?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-peligro">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Todavía no hay categorías registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection