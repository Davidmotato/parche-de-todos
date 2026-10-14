@extends('layouts.app')

@section('titulo', 'Productos')

@section('contenido')
    @php
        $esAdmin = auth()->user()->rol->nombre === 'Administrador';
    @endphp

    <div class="encabezado-pagina">
        <h2>Productos</h2>

        @if ($esAdmin)
            <a href="{{ route('productos.create') }}" class="btn btn-auto">Nuevo producto</a>
        @endif
    </div>

    <form action="{{ route('productos.index') }}" method="GET" class="buscador">
        <input type="text" name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre">
        <button type="submit" class="btn btn-auto">Buscar</button>

        @if ($busqueda)
            <a href="{{ route('productos.index') }}" class="enlace-cancelar">Quitar filtro</a>
        @endif
    </form>

    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                @if ($esAdmin)
                    <th>Precio compra</th>
                @endif
                <th>Precio venta</th>
                <th>Stock</th>
                @if ($esAdmin)
                    <th>Stock mínimo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    @if ($esAdmin)
                        <td>${{ number_format($producto->precio_compra, 0, ',', '.') }}</td>
                    @endif
                    <td>${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                    <td>{{ $producto->stock }}</td>
                    @if ($esAdmin)
                        <td>{{ $producto->stock_minimo }}</td>
                        <td>
                            <span class="etiqueta {{ $producto->estado === 'activo' ? 'etiqueta-activo' : 'etiqueta-inactivo' }}">
                                {{ ucfirst($producto->estado) }}
                            </span>
                        </td>
                        <td class="celda-acciones">
                            <a href="{{ route('productos.edit', $producto) }}" class="enlace-accion">Editar</a>

                            <form action="{{ route('productos.estado', $producto) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $producto->estado === 'activo' ? 'btn-peligro' : 'btn-enlace' }}">
                                    {{ $producto->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $esAdmin ? 8 : 4 }}">No se encontraron productos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection