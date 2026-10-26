@extends('layouts.app')

@section('titulo', 'Productos más vendidos')

@section('contenido')
    <h2>Reportes</h2>

    @include('reportes._pestanas')

    <form action="{{ route('reportes.productos') }}" method="GET" class="filtro">
        @include('reportes._fechas')
        <button type="submit" class="btn btn-auto">Ver reporte</button>
    </form>

    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Unidades vendidas</th>
                <th>Total vendido</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->unidades }}</td>
                    <td>${{ number_format($producto->total_vendido, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay ventas en el rango de fechas elegido.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="nota">Se muestran los 10 productos con más unidades vendidas en el rango.</p>
@endsection