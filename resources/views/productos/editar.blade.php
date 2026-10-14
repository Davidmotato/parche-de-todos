@extends('layouts.app')

@section('titulo', 'Editar producto')

@section('contenido')
    <h2>Editar producto</h2>

    <form action="{{ route('productos.update', $producto) }}" method="POST" class="formulario">
        @csrf
        @method('PUT')
        @include('productos._campos')

        <div class="acciones">
            <button type="submit" class="btn btn-auto">Guardar cambios</button>
            <a href="{{ route('productos.index') }}" class="enlace-cancelar">Cancelar</a>
        </div>
    </form>
@endsection