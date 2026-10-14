@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <h2>Bienvenido, {{auth()->user()->nombre}}</h2>
    <p>Desde aqui vamos a ir agregando los modulos del sistema.</p>
@endsection