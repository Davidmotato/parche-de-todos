@extends('layouts.app')

@section('titulo', 'Nueva venta')

@section('contenido')
    <h2>Nueva venta</h2>

    @if ($productos->isEmpty())
        <p>No hay productos activos con stock disponible para vender.</p>
    @else
        <form action="{{ route('ventas.store') }}" method="POST" class="formulario formulario-ancho"
              id="formulario-venta" data-lineas="{{ json_encode(old('lineas', [])) }}">
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

            <h3 class="subtitulo-seccion">Productos de la venta</h3>

            <table class="tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="lineas"></tbody>
            </table>

            <button type="button" id="agregar-linea" class="btn-enlace">+ Agregar producto</button>

            <p class="total-linea">Total: <strong id="total">$0</strong></p>

            <div class="acciones">
                <button type="submit" id="boton-guardar" class="btn btn-auto">Registrar venta</button>
                <a href="{{ route('ventas.index') }}" class="enlace-cancelar">Cancelar</a>
            </div>
        </form>

        <template id="plantilla-linea">
            <tr class="linea">
                <td>
                    <select data-campo="productos_id" required>
                        <option value="">Selecciona</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}"
                                    data-precio="{{ $producto->precio_venta }}"
                                    data-stock="{{ $producto->stock }}">
                                {{ $producto->nombre }} (stock: {{ $producto->stock }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" data-campo="cantidad" min="1" step="1" value="1" required></td>
                <td class="precio">$0</td>
                <td class="subtotal">$0</td>
                <td><button type="button" class="btn-peligro quitar-linea">Quitar</button></td>
            </tr>
        </template>

        <script>
            const formulario = document.getElementById('formulario-venta');
            const cuerpo = document.getElementById('lineas');
            const plantilla = document.getElementById('plantilla-linea');
            const totalTexto = document.getElementById('total');
            const lineasAnteriores = Object.values(JSON.parse(formulario.dataset.lineas));

            function formatoPesos(valor) {
                return '$' + Math.round(valor).toLocaleString('es-CO');
            }

            function renumerar() {
                cuerpo.querySelectorAll('.linea').forEach(function (fila, i) {
                    fila.querySelectorAll('[data-campo]').forEach(function (campo) {
                        campo.name = 'lineas[' + i + '][' + campo.dataset.campo + ']';
                    });
                });
            }

            // la cantidad no puede pasar del stock del producto elegido
            function ajustarMaximo(fila) {
                const opcion = fila.querySelector('[data-campo="productos_id"]').selectedOptions[0];
                const campoCantidad = fila.querySelector('[data-campo="cantidad"]');

                if (opcion && opcion.dataset.stock) {
                    campoCantidad.max = opcion.dataset.stock;
                } else {
                    campoCantidad.removeAttribute('max');
                }
            }

            function recalcular() {
                let total = 0;

                cuerpo.querySelectorAll('.linea').forEach(function (fila) {
                    const opcion = fila.querySelector('[data-campo="productos_id"]').selectedOptions[0];
                    const precio = opcion && opcion.dataset.precio ? parseFloat(opcion.dataset.precio) : 0;
                    const cantidad = parseFloat(fila.querySelector('[data-campo="cantidad"]').value) || 0;
                    const subtotal = precio * cantidad;

                    fila.querySelector('.precio').textContent = formatoPesos(precio);
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
                }

                ajustarMaximo(fila);
                renumerar();
                recalcular();
            }

            document.getElementById('agregar-linea').addEventListener('click', function () {
                agregarLinea();
            });

            cuerpo.addEventListener('click', function (e) {
                if (e.target.classList.contains('quitar-linea')) {
                    if (cuerpo.querySelectorAll('.linea').length > 1) {
                        e.target.closest('.linea').remove();
                        renumerar();
                        recalcular();
                    }
                }
            });

            cuerpo.addEventListener('change', function (e) {
                if (e.target.dataset.campo === 'productos_id') {
                    ajustarMaximo(e.target.closest('.linea'));
                }

                recalcular();
            });

            cuerpo.addEventListener('input', recalcular);

            // evita registrar la venta dos veces si se hace doble clic
            formulario.addEventListener('submit', function () {
                document.getElementById('boton-guardar').disabled = true;
            });

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