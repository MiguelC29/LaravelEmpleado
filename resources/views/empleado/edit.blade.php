@extends('layouts.app')
@section('content')
<div class="container">
<!-- INCLUIMOS EL FORMULARIO CON LA FUNCION BLADE INCLUDE LE PASAMOS LA CARPETA EMPLEADO Y LUEGO DEL PUNTO EL ARCHIVO FORM-->

<form action="{{ url('/empleado/'.$empleado->id) }}" method="post" enctype="multipart/form-data">
    <!-- AL IGUAL QUE HICIMOS EN EL INDEX EN EL BTN BORRAR AGREGAMOS EL TOKEN Y MODIFICAMOS EL METODO -->
    @csrf
    {{ method_field('PATCH') }}

    <!-- DEBEMOS PASAR INFORMACION A LA INCLUCION (FORMULARIO):
    CREAMOS LA VARIABLE MODO EL CUAL TENDRA EL VALOR DE EDITAR. ASI CUANDO ABRAMOS EL FORM DE EDITAR EL BTN DE SUBMIT DIRA EDITAR DATOS -->
    @include('empleado.form', ['modo'=>'Editar'])

</form>
</div>
@endsection