@extends('layouts.app')

@section('titulo', 'Nueva categoría')

@section('contenido')
    <h2>Nueva categoría</h2>

    <form action="{{ route('categorias.store') }}" method="POST" class="formulario">
        @csrf
        @include('categorias._campos')

        <div class="acciones">
            <button type="submit" class="btn btn-auto">Guardar</button>
            <a href="{{ route('categorias.index') }}" class="enlace-cancelar">Cancelar</a>
        </div>
    </form>
@endsection