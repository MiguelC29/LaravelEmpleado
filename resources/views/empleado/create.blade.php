@extends('layouts.app')
@section('content')
<div class="container">

<form action="{{ url('/empleado') }}" method="post" enctype="multipart/form-data">

    <!-- Laravel nos pide que usemos un identificador(llave de seguridad) para que se sepa que este formulario esta viniendo del mismo sistema-->
    <!-- Genera un token para el control de seguridad entre lo que se esta respetando, es decir el formulario, y la recepcion de los datos. Si no existe este token y no coincide con un control interno del framework, va a ser rechazado -->
    @csrf
    <!-- INCLUIMOS EL FORMULARIO CON LA FUNCION BLADE INCLUDE LE PASAMOS LA CARPETA EMPLEADO Y LUEGO DEL PUNTO EL ARCHIVO FORM-->
    <!-- DEBEMOS PASAR INFORMACION A LA INCLUCION (FORMULARIO):
    CREAMOS LA VARIABLE MODO EL CUAL TENDRA EL VALOR DE CREAR. ASI CUANDO ABRAMOS EL FORM DE EDITAR EL BTN DE SUBMIT DIRA CREAR DATOS -->
    @include('empleado.form',['modo'=>'Crear'])
</form>
</div>
@endsection