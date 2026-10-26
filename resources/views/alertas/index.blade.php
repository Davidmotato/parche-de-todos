@extends('layouts.app')

@section('titulo', 'Alertas de stock')

@section('contenido')
    <div class="encabezado-pagina">
        <h2>Alertas de stock mínimo</h2>
        <a href="{{ route('entradas.create') }}" class="btn btn-auto">Registrar entrada</a>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Stock mínimo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>{{ $producto->stock_minimo }}</td>
                    <td>
                        @if ($producto->stock == 0)
                            <span class="etiqueta etiqueta-agotado">Agotado</span>
                        @else
                            <span class="etiqueta etiqueta-bajo">Bajo</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay productos con stock bajo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection