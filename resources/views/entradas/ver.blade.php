@extends('layouts.app')

@section('titulo', 'Entrada ' . $entrada->id)

@section('contenido')
    <div class="encabezado-pagina">
        <h2>Entrada N° {{ $entrada->id }}</h2>
        <a href="{{ route('entradas.index') }}" class="enlace-cancelar">Volver a la lista</a>
    </div>

    <div class="datos-resumen">
        <p><strong>Proveedor:</strong> {{ $entrada->proveedor->nombre }}</p>
        <p><strong>Fecha:</strong> {{ $entrada->fecha->format('d/m/Y h:i A') }}</p>
        <p><strong>Registrada por:</strong> {{ $entrada->usuario->nombre }}</p>
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
            @foreach ($entrada->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                    <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total-linea">Total: <strong>${{ number_format($entrada->total, 0, ',', '.') }}</strong></p>
@endsection