<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - El Parche de Todos</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body class="pagina-login">
    <div class="caja-login">
        <h1>El Parche de Todos</h1>
        <p class="subtitulo">Sistema de gestión de inventario y venta</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <label for="email">Correo</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror

            <button type="submit" class="btn">Ingresar</button>
        </form>
    </div>
</body>
</html>