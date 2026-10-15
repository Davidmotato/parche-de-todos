@extends('layouts.app')

@section('titulo', 'Editar proveedor')

@section('contenido')
    <h2>Editar proveedor</h2>

    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST" class="formulario">
        @csrf
        @method('PUT')
        @include('proveedores._campos')

        <div class="acciones">
            <button type="submit" class="btn btn-auto">Guardar cambios</button>
            <a href="{{ route('proveedores.index') }}" class="enlace-cancelar">Cancelar</a>
        </div>
    </form>
@endsection