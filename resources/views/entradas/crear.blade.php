@extends('layouts.app')

@section('titulo', 'Nueva entrada')

@section('contenido')
    <h2>Nueva entrada de mercancía</h2>

    @if ($proveedores->isEmpty() || $productos->isEmpty())
        <p>Para registrar una entrada necesitas tener al menos un proveedor y un producto activo.</p>
    @else
        <form action="{{ route('entradas.store') }}" method="POST" class="formulario formulario-ancho"
            id="formulario-entrada" data-lineas="{{ json_encode(old('lineas', [])) }}">
            @csrf

            @if ($errors->any())
                <div class="alerta alerta-error">
                    <ul>
                        @foreach ($errors->all() as $mensaje)
                            <li>{{ $mensaje }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="proveedores_id">Proveedor</label>
            <select id="proveedores_id" name="proveedores_id" required>
                <option value="">Selecciona un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}"
                        {{ old('proveedores_id') == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->nombre }}
                    </option>
                @endforeach
            </select>

            <label for="fecha">Fecha y hora</label>
            <input type="datetime-local" id="fecha" name="fecha"
                   value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}" required>

            <h3 class="subtitulo-seccion">Productos recibidos</h3>

            <table class="tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="lineas"></tbody>
            </table>

            <button type="button" id="agregar-linea" class="btn-enlace">+ Agregar producto</button>

            <p class="total-linea">Total: <strong id="total">$0</strong></p>

            <div class="acciones">
                <button type="submit" class="btn btn-auto">Registrar entrada</button>
                <a href="{{ route('entradas.index') }}" class="enlace-cancelar">Cancelar</a>
            </div>
        </form>

        <template id="plantilla-linea">
            <tr class="linea">
                <td>
                    <select data-campo="productos_id" required>
                        <option value="">Selecciona</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio_compra }}">
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" data-campo="cantidad" min="1" step="1" value="1" required></td>
                <td><input type="number" data-campo="precio_unitario" min="0" step="0.01" required></td>
                <td class="subtotal">$0</td>
                <td><button type="button" class="btn-peligro quitar-linea">Quitar</button></td>
            </tr>
        </template>

        <script>
            const cuerpo = document.getElementById('lineas');
            const plantilla = document.getElementById('plantilla-linea');
            const totalTexto = document.getElementById('total');
            const lineasAnteriores = Object.values(JSON.parse(document.getElementById('formulario-entrada').dataset.lineas));

            function formatoPesos(valor) {
                return '$' + Math.round(valor).toLocaleString('es-CO');
            }

            // los nombres lineas[0][...], lineas[1][...] se ponen aquí para que siempre vayan en orden
            function renumerar() {
                cuerpo.querySelectorAll('.linea').forEach(function (fila, i) {
                    fila.querySelectorAll('[data-campo]').forEach(function (campo) {
                        campo.name = 'lineas[' + i + '][' + campo.dataset.campo + ']';
                    });
                });
            }

            function recalcular() {
                let total = 0;

                cuerpo.querySelectorAll('.linea').forEach(function (fila) {
                    const cantidad = parseFloat(fila.querySelector('[data-campo="cantidad"]').value) || 0;
                    const precio = parseFloat(fila.querySelector('[data-campo="precio_unitario"]').value) || 0;
                    const subtotal = cantidad * precio;

                    fila.querySelector('.subtotal').textContent = formatoPesos(subtotal);
                    total += subtotal;
                });

                totalTexto.textContent = formatoPesos(total);
            }

            function agregarLinea(datos) {
                const fila = plantilla.content.cloneNode(true).querySelector('.linea');
                cuerpo.appendChild(fila);

                if (datos) {
                    fila.querySelector('[data-campo="productos_id"]').value = datos.productos_id || '';
                    fila.querySelector('[data-campo="cantidad"]').value = datos.cantidad || 1;
                    fila.querySelector('[data-campo="precio_unitario"]').value = datos.precio_unitario || '';
                }

                renumerar();
                recalcular();
            }

            document.getElementById('agregar-linea').addEventListener('click', function () {
                agregarLinea();
            });

            cuerpo.addEventListener('click', function (e) {
                if (e.target.classList.contains('quitar-linea')) {
                    // siempre debe quedar al menos una fila
                    if (cuerpo.querySelectorAll('.linea').length > 1) {
                        e.target.closest('.linea').remove();
                        renumerar();
                        recalcular();
                    }
                }
            });

            // al escoger un producto, se rellena el precio con su precio de compra actual
            cuerpo.addEventListener('change', function (e) {
                if (e.target.dataset.campo === 'productos_id') {
                    const opcion = e.target.selectedOptions[0];
                    const fila = e.target.closest('.linea');

                    if (opcion && opcion.dataset.precio) {
                        fila.querySelector('[data-campo="precio_unitario"]').value = opcion.dataset.precio;
                    }
                }

                recalcular();
            });

            cuerpo.addEventListener('input', recalcular);

            if (lineasAnteriores.length > 0) {
                lineasAnteriores.forEach(function (linea) {
                    agregarLinea(linea);
                });
            } else {
                agregarLinea();
            }
        </script>
    @endif
@endsection