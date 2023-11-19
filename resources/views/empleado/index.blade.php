@extends('layouts.app')
@section('content')
<div class="container">

@if(Session::has('mensaje'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ Session::get('mensaje') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<a href="{{ url('empleado/create') }}" class="btn btn-success">Registrar nuevo empleado</a>
<br><br>
<table class="table table-light">
    
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    
    <tbody>
        <!--blade Foreach para recorrer los datos. Consultar la info, la variable $empleados viene del controlador y se recupera como si fuera un empleado-->
        @foreach($empleados as $empleado)
        <tr>
            <!-- estos campos deben concidir a como se llaman en la bd -->
            <td>{{ $empleado->id }}</td>
            <!-- VAMOS A MODIFICAR ESTO PARA MOSTRAR EN VEZ DEL NOMBRE, LA IMG COMO TAL -->
            <td>
                <!-- AÑADIMOS LA ETIQUETA IMG, LUEGO USAMOS ASSET QUE NOS DA ACCESO A EL DEPOSITO, EL DEPOSITO SE LLAMA STORAGE(ESTAMOS ACCEDIENDO A LA CARPETA DONDE ESTAN LAS IMG) Y LE CONCATENAMOS EL NOMBRE DE LA FOTO-->
                <img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto }}" width="100" alt="">
                <!-- PARA QUE ESTO FUNCIONE CORRECTAMENTE DEBEMOS ABRIR LA CONSOLA Y EJECUTAMOS LO SIGUIENTE php artisan storage:link 
                ESTO LO QUE HACE ES ABRIR UN ENLACE PARA COMUNICARSE CON ESTA CARPETA-->
                <!-- {{ $empleado->Foto }} -->
            </td>
            <td>{{ $empleado->Nombre }}</td>
            <td>{{ $empleado->ApellidoPaterno }}</td>
            <td>{{ $empleado->ApellidoMaterno }}</td>
            <td>{{ $empleado->Correo }}</td>
            <td>
                <!-- CREAMOS UNA URL PARA QUE NOS REDIRECCIONE USAMOS LA MISMA URL DEL BTN BORRAR PERO LE AÑADIMOS EL /EDIT YA QUE SI EJECUTAMOS EL COMANDO php artisan route:list VEREMOS QUE ES NECESARIO -->
                <a href="{{ url('/empleado/'.$empleado->id.'/edit') }}" class="btn btn-warning">Editar</a>
                | 
                <form action="{{ url('/empleado/'.$empleado->id) }}" class="d-inline" method="post">
                    <!-- PARA LA RECEPCION DE LOS DATOS DE LOS FORMULARIOS LARAVEL SIEMPRE NOS PIDE UNA LLAVE O TOKEN, ESTO CON EL FIN DE EVITAR QUE CUALQUIER FORMULARIO PUEDA ENVIAR INFORMACION -->
                    @csrf
                    <!-- SI EJECUTAMOS EL COMANDO php artisan route:list VEREMOS QUE PARA EJECUTAR EL DESTROY NECESITAMOS EL METODO DELETE POR LO TANTO DEBEMOS CONVERTIR EL METODO POST DEL FORMULARIO A UN METODO DELETE -->
                    {{ method_field('DELETE') }}
                    <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" value="Borrar">
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{!! $empleados->links() !!}
</div>
@endsection