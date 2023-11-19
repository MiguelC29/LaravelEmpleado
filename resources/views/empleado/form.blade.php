<!-- Formulario que tendrá los datos en común con create y edit
PASAMOS EL FORMULARIO QUE ANTERIORMENTE ESTABA EN CREATE.BLADE PARA REUTILIZAR ESTE FORMULARIO EN CREATE Y EDIT-->

<!-- paso de informacion atraves de las vistas, en este caso ponemos un h1 con la varible modo que de acuerdo a lo seleccionado me mostrara o crear o editar -->
<h1>{{ $modo }} empleado</h1>
<!-- AGREGAMOS EL VALUE PARA QUE AL MOMENTO DE EDITAR ME RETORNE LOS DATOS ESPECIFICOS -->

<!-- AHORA VAMOS A MOSTRAR LOS MENSAJES DE ERROR EN CASO DE QUE FALTE ALGUN CAMPO -->
@if(count($errors)>0)

    <div class="alert alert-danger" role="alert">
        <!-- lista de errores -->
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@endif

<div class="form-group">
<label for="Nombre">Nombre</label>
<!-- USAMOS EL ISSET PARA VALIDAR SI HAY ALGO, SI HAY ALGO LO IMPRIME DE LO CONTRARIO NO PONE NADA, ESTO SE HACE POR LO QUE ESE FORMULARIO LO USAMOS TANTO PARA REGISTRO COMO PARA ACTUALIZAR, Y SI DEJAMOS EL SOLO $EMPLEADO->NOMBRE NOS DARIA ERROR A LA HORA DE HACER UN REGISTRO, YA QUE ES VA INTENTAR ACCEDER A UN VALOR -->
<!-- AHORA VAMOS A MODIFICAR EL VALUE PARA QUE SI SOLO DIGITO UN VALOR Y LE DOY ENVIAR ME MUESTRE LOS ERRORES Y ME MANTENGA LOS DATOS QUE ESTOY ESCRIBIENDO -->
<input class="form-control" type="text" name="Nombre" value="{{ isset($empleado->Nombre)?$empleado->Nombre:old('Nombre') }}" id="Nombre">
<br>
</div>

<div class="form-group">
<label for="ApellidoPaterno">Apellido Paterno</label>
<input class="form-control" type="text" name="ApellidoPaterno" value="{{ isset($empleado->ApellidoPaterno)?$empleado->ApellidoPaterno:old('ApellidoPaterno') }}" id="ApellidoPaterno">
<br>
</div>

<div class="form-group">
<label for="ApellidoMaterno">Apellido Materno</label>
<input class="form-control" type="text" name="ApellidoMaterno" value="{{ isset($empleado->ApellidoMaterno)?$empleado->ApellidoMaterno:old('ApellidoMaterno') }}" id="ApellidoMaterno">
<br>
</div>

<div class="form-group">
<label for="Correo">Correo</label>
<input class="form-control" type="text" name="Correo" value="{{ isset($empleado->Correo)?$empleado->Correo:old('Correo') }}" id="Correo">
<br>
</div>

<div class="form-group">
<label for="Foto">Foto</label>
<!-- COMO VEMOS NO SE MUESTRA NI LA IMAGEN NI LA RUTA O NOMBRE DE LA IMG POR ESO DEBEMOS HACER LOS SIGUIENTE, LE PASAMOS LA RUTA DEL VALUE ANTES DEL INPUT-->
<!-- agregamos lo mismo que en index para mostrar la foto -->
@if(isset($empleado->Foto))
<img class="img-thumbnail img-fluid" src="{{ asset('storage').'/'.$empleado->Foto }}" width="100" alt="">
@endif
<input class="form-control" type="file" name="Foto" value="" id="Foto">
<br>
</div>
<!-- modo es para de acuerdo al modo si es editar o agregar me muestra el texto correspondiente-->
<input class="btn btn-success" type="submit" value="{{$modo}} datos">
<a class="btn btn-primary" href="{{ url('empleado') }}">Regresar</a>
<br>