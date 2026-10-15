@extends('layouts.app')

@section('titulo', 'Entradas')

@section('contenido')
    <div class="encabezado-pagina">
        <h2>Entradas de mercancía</h2>
        <a href="{{ route('entradas.create') }}" class="btn btn-auto">Nueva entrada</a>
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Registrada por</th>
                <th>Total</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($entradas as $entrada)
                <tr>
                    <td>{{ $entrada->id }}</td>
                    <td>{{ $entrada->fecha->format('d/m/Y h:i A') }}</td>
                    <td>{{ $entrada->proveedor->nombre }}</td>
                    <td>{{ $entrada->usuario->nombre }}</td>
                    <td>${{ number_format($entrada->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('entradas.show', $entrada) }}" class="enlace-accion">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Todavía no hay entradas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection