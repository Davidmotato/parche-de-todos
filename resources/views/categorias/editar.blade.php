@extends('layouts.app')

@section('titulo', 'Editar categoría')

@section('contenido')
    <h2>Editar categoría</h2>

    <form action="{{ route('categorias.update', $categoria) }}" method="POST" class="formulario">
        @csrf
        @method('PUT')
        @include('categorias._campos')

        <div class="acciones">
            <button type="submit" class="btn btn-auto">Guardar cambios</button>
            <a href="{{ route('categorias.index') }}" class="enlace-cancelar">Cancelar</a>
        </div>
    </form>
@endsection