<div class="pestanas">
    <a href="{{ route('reportes.ventas') }}"
       class="{{ request()->routeIs('reportes.ventas') ? 'activa' : '' }}">Ventas y ganancias</a>
    <a href="{{ route('reportes.productos') }}"
       class="{{ request()->routeIs('reportes.productos') ? 'activa' : '' }}">Más vendidos</a>
    <a href="{{ route('reportes.movimientos') }}"
       class="{{ request()->routeIs('reportes.movimientos') ? 'activa' : '' }}">Movimientos</a>
</div>