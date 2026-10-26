@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <h2>Bienvenido, {{ auth()->user()->nombre }}</h2>

    @if (auth()->user()->rol->nombre === 'Administrador')
        @if ($totalAlertas > 0)
            <div class="panel-alerta">
                <h3>Productos con stock bajo ({{ $totalAlertas }})</h3>

                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productosAlerta as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
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
                        @endforeach
                    </tbody>
                </table>

                <p class="total-linea">
                    <a href="{{ route('alertas.index') }}" class="enlace-accion">Ver todas las alertas</a>
                </p>
            </div>
        @else
            <p class="mensaje-ok">Todo el inventario está por encima del stock mínimo.</p>
        @endif
    @else
        <p>Desde el menú puedes registrar ventas y consultar los productos.</p>
    @endif
@endsection