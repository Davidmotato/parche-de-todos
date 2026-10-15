@extends('layouts.app')

@section('titulo', 'Proveedores')

@section('contenido')
    <div class="encabezado-pagina">
        <h2>Proveedores</h2>
        <a href="{{ route('proveedores.create') }}" class="btn btn-auto">Nuevo proveedor</a>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Entradas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->telefono ?? '-' }}</td>
                    <td>{{ $proveedor->direccion ?? '-' }}</td>
                    <td>{{ $proveedor->entradas_count }}</td>
                    <td class="celda-acciones">
                        <a href="{{ route('proveedores.edit', $proveedor) }}" class="enlace-accion">Editar</a>

                        <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar este proveedor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-peligro">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Todavía no hay proveedores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection