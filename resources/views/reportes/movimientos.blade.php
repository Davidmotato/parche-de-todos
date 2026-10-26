@extends('layouts.app')

@section('titulo', 'Movimientos de inventario')

@section('contenido')
    <h2>Reportes</h2>

    @include('reportes._pestanas')

    <form action="{{ route('reportes.movimientos') }}" method="GET" class="filtro">
        @include('reportes._fechas')

        <div class="campo-filtro">
            <label for="producto">Producto</label>
            <select id="producto" name="producto">
                <option value="">Todos</option>
                @foreach ($listaProductos as $producto)
                    <option value="{{ $producto->id }}" {{ $productoId == $producto->id ? 'selected' : '' }}>
                        {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-auto">Ver reporte</button>
    </form>

    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Responsable</th>
                <th>Documento</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->fecha->format('d/m/Y h:i A') }}</td>
                    <td>
                        @if ($movimiento->tipo === 'Entrada')
                            <span class="etiqueta etiqueta-activo">Entrada</span>
                        @else
                            <span class="etiqueta etiqueta-bajo">Venta</span>
                        @endif
                    </td>
                    <td>{{ $movimiento->producto }}</td>
                    <td>{{ $movimiento->tipo === 'Entrada' ? '+' : '-' }}{{ $movimiento->cantidad }}</td>
                    <td>{{ $movimiento->usuario }}</td>
                    <td>
                        @if ($movimiento->tipo === 'Entrada')
                            <a href="{{ route('entradas.show', $movimiento->documento) }}" class="enlace-accion">Entrada N° {{ $movimiento->documento }}</a>
                        @else
                            <a href="{{ route('ventas.show', $movimiento->documento) }}" class="enlace-accion">Venta N° {{ $movimiento->documento }}</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay movimientos en el rango de fechas elegido.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($movimientos->count() >= 300)
        <p class="nota">Se muestran los 300 movimientos más recientes. Acota las fechas o elige un producto para ver menos.</p>
    @endif

    <p class="nota">Los ajustes de stock hechos a mano al editar un producto no aparecen aquí, solo las entradas y las ventas.</p>
@endsection