@extends('layouts.app')

@section('titulo', 'Reporte de ventas y ganancias')

@section('contenido')
    <h2>Reportes</h2>

    @include('reportes._pestanas')

    <form action="{{ route('reportes.ventas') }}" method="GET" class="filtro">
        @include('reportes._fechas')
        <button type="submit" class="btn btn-auto">Ver reporte</button>
    </form>

    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>N° de ventas</th>
                <th>Total vendido</th>
                <th>Costo</th>
                <th>Ganancia</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($filas as $fila)
                <tr>
                    <td>{{ $fila->dia->format('d/m/Y') }}</td>
                    <td>{{ $fila->num_ventas }}</td>
                    <td>${{ number_format($fila->total_vendido, 0, ',', '.') }}</td>
                    <td>${{ number_format($fila->costo, 0, ',', '.') }}</td>
                    <td class="{{ $fila->ganancia < 0 ? 'texto-negativo' : '' }}">
                        ${{ number_format($fila->ganancia, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay ventas en el rango de fechas elegido.</td>
                </tr>
            @endforelse

            @if ($filas->isNotEmpty())
                <tr class="fila-total">
                    <td>Total</td>
                    <td>{{ $totales['ventas'] }}</td>
                    <td>${{ number_format($totales['vendido'], 0, ',', '.') }}</td>
                    <td>${{ number_format($totales['costo'], 0, ',', '.') }}</td>
                    <td>${{ number_format($totales['ganancia'], 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p class="nota">La ganancia es el total vendido menos el costo, calculado con el precio de compra actual de cada producto.</p>
@endsection