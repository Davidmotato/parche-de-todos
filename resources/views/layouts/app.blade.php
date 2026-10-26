<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'El Parche de Todos')</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
    <header class="barra-superior">
        <h1 class="logo">El Parche de Todos</h1>

        <div class="usuario-info">
            <span>{{ auth()->user()->nombre }} ({{ auth()->user()->rol->nombre }})</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-salir">Cerrar sesión</button>
            </form>
        </div>
    </header>

<nav class="menu">
    <a href="{{ route('inicio') }}">Inicio</a>
    <a href="{{ route('ventas.index') }}">Ventas</a>
    <a href="{{ route('productos.index') }}">Productos</a>

    @if (auth()->user()->rol->nombre === 'Administrador')
        <a href="{{ route('categorias.index') }}">Categorías</a>
        <a href="{{ route('proveedores.index') }}">Proveedores</a>
        <a href="{{ route('entradas.index') }}">Entradas</a>
        <a href="{{ route('reportes.ventas') }}">Reportes</a>
        <a href="{{ route('alertas.index') }}">    
            Alertas
            @if ($alertasStock > 0)
                <span class="insignia">{{ $alertasStock }}</span>
                
            @endif
        </a>
    @endif
</nav>        

    <main class="contenido">
        @if (session('exito'))
            <div class="alerta alerta-exito">{{ session('exito')}}</div>            
        @endif

        @if (session('error'))
            <div class="alerta alerta-error">{{ session('error')}}</div>    
        @endif
        
        @yield('contenido')
    </main>
</body>
</html>