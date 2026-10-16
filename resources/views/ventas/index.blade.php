@extends('layouts.app')

@section('titulo', 'Ventas')

@section('contenido')
    @php
        $esAdmin = auth()->user()->rol->nombre === 'Administrador';
    @endphp

    <div class="encabezado-pagina">
        <h2>Ventas</h2>
        <a href="{{ route('ventas.create') }}" class="btn btn-auto">Nueva venta</a>
    </div>

    <form action="{{ route('ventas.index') }}" method="GET" class="buscador">
        <input type="date" name="fecha" value="{{ $fecha }}">
        <button type="submit" class="btn btn-auto">Ver día</button>
    </form>

    <table class="tabla">
        <thead>
            <tr>
                <th>N°</th>
                <th>Hora</th>
                @if ($esAdmin)
                    <th>Vendedor</th>
                @endif
                <th>Total</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->fecha->format('h:i A') }}</td>
                    @if ($esAdmin)
                        <td>{{ $venta->usuario->nombre }}</td>
                    @endif
                    <td>${{ number_format($venta->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="enlace-accion">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $esAdmin ? 5 : 4 }}">No hay ventas registradas en esta fecha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total-linea">Total del día: <strong>${{ number_format($totalDia, 0, ',', '.') }}</strong></p>
@endsection