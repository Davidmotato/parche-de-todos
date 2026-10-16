@extends('layouts.app')

@section('titulo', 'Venta ' . $venta->id)

@section('contenido')
    <h2>Venta N° {{ $venta->id }}</h2>

    <div class="datos-resumen">
        <p><strong>Fecha:</strong> {{ $venta->fecha->format('d/m/Y h:i A') }}</p>
        <p><strong>Vendedor:</strong> {{ $venta->usuario->nombre }}</p>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                    <td>${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total-linea">Total: <strong>${{ number_format($venta->total, 0, ',', '.') }}</strong></p>

    <div class="botones-comprobante no-imprimir">
        <a href="{{ route('ventas.create') }}" class="btn btn-auto">Nueva venta</a>
        <button type="button" class="btn btn-auto" onclick="window.print()">Imprimir</button>
        <a href="{{ route('ventas.index') }}" class="enlace-cancelar">Volver a la lista</a>
    </div>
@endsection