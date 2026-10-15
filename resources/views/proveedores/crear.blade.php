@extends('layouts.app')

@section('titulo', 'Nuevo proveedor')

@section('contenido')
    <h2>Nuevo proveedor</h2>

    <form action="{{ route('proveedores.store') }}" method="POST" class="formulario">
        @csrf
        @include('proveedores._campos')

        <div class="acciones">
            <button type="submit" class="btn btn-auto">Guardar</button>
            <a href="{{ route('proveedores.index') }}" class="enlace-cancelar">Cancelar</a>
        </div>
    </form>
@endsection