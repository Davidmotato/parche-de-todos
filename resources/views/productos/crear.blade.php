@extends('layouts.app')

@section('titulo', 'Nuevo producto')

@section('contenido')
    <h2>Nuevo producto</h2>

    @if ($categorias->isEmpty())
        <p>Primero debes crear al menos una categoría.
            <a href="{{ route('categorias.create') }}">Crear categoría</a>
        </p>
    @else
        <form action="{{ route('productos.store') }}" method="POST" class="formulario">
            @csrf
            @include('productos._campos')

            <div class="acciones">
                <button type="submit" class="btn btn-auto">Guardar</button>
                <a href="{{ route('productos.index') }}" class="enlace-cancelar">Cancelar</a>
            </div>
        </form>
    @endif
@endsection