<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //CONSULTAR INFORMACION DE LA BASE DE DATOS
        //VARIABLE QUE NOS VA PERMITIR CONSULTAR LOS DATOS ATRAVES DEL INDEX
        //EN ESTA VARIABLE ALMACENO LA INFORMACION DE LA BD Y SE LA PASO AL INDEX
        //SE CONSULTA TODA LA INFORMACION A PARTIR DE LA FUENTE O EL MODELO Empleado Y SE TOMAN LOS PRIMEROS 5 REGISTROS. SE ALMACENA ESTO EN UNA VARIABLE que se va llamar empleados para acceder directamente desde el index
        $datos['empleados']=Empleado::paginate(1); //PAGINATE->PAGINADO EN ESTE CASO QUE SOLO MUESTRE 5 RESULTADOS
        return view('empleado.index', $datos); //vista y le pasamos los datos
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //VALIDACION DE CAMPOS AL REALIZAR EL REGISTRO
        //VARIABLE QUE CONTIENE UNA LISTA CON LOS CAMPOS QUE QUIERO VALIDAR
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg'
        ];

        //AHORA LOS MENSAJES QUE QUEREMOS MOSTRAR
        //required le decimos que a todos los que sean requeridos muestre ese mensaje el :atribute es un comodin que se reemplaza con el campo que no este digitado
        $mensaje=[
            'required'=>'El :attribute es requerido',
            'Foto.required'=>'La foto es requerida'
        ];
        
        //AHORA VAMOS A UNIR
        $this->validate($request, $campos, $mensaje);

        /*RECOLECTAR DATOS QUE ENVIEN A TRAVES DEL FORMULARIO, QUITELE EL TOKEN, Y CON ESO USE EL MODELO Y HAGA LA INSERCION EN LA BASE DE DATOS*/

        //$datosEmpleado = request()->all(); //TODOS LOS REGISTROS QUE ENVIE SE ALMACENAN EN LA VARIABLE
        $datosEmpleado = request()->except('_token'); //PARA OMITIR EL TOKEN A LA HORA DE ALMACENAR LOS DATOS EN JSON. EN ESTA SENTENCIA SE GUARDAN TODOS LOS DATOS EXCEPTO EL TOKEN
        //TENEMOS UN MODELO DISPONIBLE DONDE PODEMOS USAR LA INSERCION

        //LA FOTO SE ALMACENA CON UNA EXTENSION TMP->ARCHIVO ALMACENADO TEMPORALMENTE, ENTONCES VAMOS A CAMBIARLA A JPG

        if ($request->hasFile('Foto')) { //SI EXISTE UN ARCHIVO, SI YA SE AGREGO UN ARCHIVO(FOTO) AL FORMULARIO
            //MODIFICANDO EL NOMBRE Y AGREGANDO/ADJUNTANDO AL SISTEMA/PROYECTO
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public');
                //ALTERARCAMPO       //USARNOMBRECAMPO     //INSERTAR EN EL STORAGE RUTA UPLOADS
            //GUARDAMOS LA FOTO EN EL STORAGE QUE ES UNA CARPETA DONDE SE ALMACENAN LOS ARCHIVOS
        }

        Empleado::insert($datosEmpleado); //INSERTA LOS CAMPOS ENVIADOS(ALMACENADOS EN LA VARIABLE DATOSEMPLEADO) Y LOS INSERTA EN LA BD
        //return response()->json($datosEmpleado); //DEVUELVE LOS DATOS EN FORMATO JSON
        //REDIRECCIONAMOS AL INDEX Y LE MOSTRAMOS UN MENSAJE DE QUE EL EMPLEADO FUE AGREGADO
        return redirect('empleado')->with('mensaje', 'Empleado agregado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    //public function edit(Empleado $empleado) REMPLEAZAMOS POR ID PARA RECEPCIONAR EL ID QUE LE MANDAMOS POR LA URL
    public function edit($id)
    {
        //
        //RECUPERAR DATOS MEDIANTE EL ID CON EL METODO FIND
        $empleado=Empleado::findOrFail($id); //BUSCAR LA INFO APARTIR DEL ID

        return view('empleado.edit', compact('empleado')); //RETORNAMOS A ESA VISTA Y LE PASAMOS LA INFO
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        //VALIDACION DE CAMPOS AL REALIZAR EL REGISTRO
        //VARIABLE QUE CONTIENE UNA LISTA CON LOS CAMPOS QUE QUIERO VALIDAR
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
        ];

        //AHORA LOS MENSAJES QUE QUEREMOS MOSTRAR
        //required le decimos que a todos los que sean requeridos muestre ese mensaje el :atribute es un comodin que se reemplaza con el campo que no este digitado
        $mensaje=[
            'required'=>'El :attribute es requerido',
        ];
        
        //ANTES DE ESTO DEBEMOS VALIDAR SI YA EXISTE UNA FOTO, SI EXISTE NO ES OBLIGATORIO QUE LA RENUEVE, PERO DONDE NO EXISTA, EL CAMPO SERA REQUERIDO
        if($request->hasFile('Foto')) {
            $campos=['Foto'=>'required|max:10000|mimes:jpeg,png,jpg'];
            $mensaje=['Foto.required'=>'La foto es requerida'];
        }
        
        //AHORA VAMOS A UNIR
        $this->validate($request, $campos, $mensaje);

        //LE QUITAMOS EL TOKEN Y EL METHOD QUE NOS DA EL FORMULARIO PARA QUE NO ALMACENE ESTOS 2 DATOS
        //ESTE TOKEN Y METHOD LO EVIDENCIAMOS EN EL ARCHIVO EDIT
        $datosEmpleado = request()->except(['_token', '_method']);
        //USAMOS EL MODELO EMPLEADO
        //PREGUNTAMOS SI REALMENTE COINCIDE CON EL ID
        //BUSCA LA INFO DEL ID Y SI COINCIDE CON EL ID QUE LE ESTOY PASANDO, UNA VEZ LO ENCUENTRE HACE EL UPDATE CON LOS DATOS DE EMPLEADO

        // PARA PODER ACTUALIZAR LA IMG TENEMOS QUE CONCIDERAR QUE TENEMOS UNA FOTO ANTIGUA Y LA VAMOS A REMPLAZAR POR UNA NUEVA, LO QUE SIGNIFICA QUE TENEMOS QUE BORRAR LA FOTO ANTIGUA Y ACTUALIZARLA POR LA FOTO NUEVA, PERO UNICAMENTE SI EL USUARIO LE DA AL BTN DE ELEGIR ARCHIVO EN EL FORMULARIO Y AGREGO UNA IMG, SINO SELECCIONO NINGUNA FOTO, ENTONCES SE CONSERVA LA IMG QUE YA TENIA EN EL REGISTRO

        // AQUI LO QUE HACEMOS ES RECOLECTAR LOS DATOS EN LA LINEA ANTERIOR, LUEGO CON EL HASFILE LE PREGUNTAMOS SI ESA INFORMACION EXITE SI EXISTE UNA FOTO Y SI EXISTE, VA ADJUNTARLA Y PASARLE EL NOMBRE A ESE CAMPO Y DESPUES HACE LA ACTUALIZACION.
        // SIN EMBARGO SI NO SE CUMPLE EL IF ES DECIR SI NO EXISTE ESA FOTO PUES LA ACTUALIZACION SE HACE CON LOS DATOS QUE YA HAY
        if ($request->hasFile('Foto')) { //SI EXISTE UN ARCHIVO, SI YA SE AGREGO UN ARCHIVO(FOTO) AL FORMULARIO
            // SOLO FALTARIA ANTES DE SUBIR LA NUEVA FOTO BORRAR LA ANTIGUA. PARA HACER EL BORRADO DEBEMOS INCLUIR UNA CLASE QUE CONTIENE VARIOS ELEMENTOS QUE NOS VAN A PERMITIR BORRAR (LINEA 8)
            $empleado=Empleado::findOrFail($id); //BUSCAR LA INFO APARTIR DEL ID RECUPERAR INFO
            
            Storage::delete('public/'.$empleado->Foto); //BORRAMOS LA FOTO ANTIGUA

            //MODIFICANDO EL NOMBRE Y AGREGANDO/ADJUNTANDO AL SISTEMA/PROYECTO
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads', 'public');
                //ALTERARCAMPO       //USARNOMBRECAMPO     //INSERTAR EN EL STORAGE RUTA UPLOADS
            //GUARDAMOS LA FOTO EN EL STORAGE QUE ES UNA CARPETA DONDE SE ALMACENAN LOS ARCHIVOS
        }

        Empleado::where('id', '=', $id)->update($datosEmpleado);

        // return redirect('empleado');

        $empleado=Empleado::findOrFail($id); //BUSCAR LA INFO APARTIR DEL ID

        //return view('empleado.edit', compact('empleado')); //RETORNAMOS A ESA VISTA Y LE PASAMOS LA INFO ACTUALIZADA
        return redirect('empleado')->with('mensaje', 'Empleado Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    //public function destroy(Empleado $empleado) CAMBIAMOS EMPLEADO POR UN ID
    public function destroy($id)
    {
        //
        $empleado=Empleado::findOrFail($id); //BUSCAR LA INFO APARTIR DEL ID RECUPERAR LA INFO PARA SABER QUE FOTO HAY QUE ELIMINAR DEL STORAGE

        //PREGUNTAR SI ESA FOTO EXISTE Y SI EXISTE LA ELIMINAMOS
        if(Storage::delete('public/'.$empleado->Foto)) {
            //SE HACE USO DEL MODELO Y LE DIGO QUE ME DESTRUYA CON UN ID QUE LE VOY A PASAR ESE ID QUE RECIBE ES EL MISMO QUE LE PASAMOS POR EL FORMULARIO DEL BTN BORRAR EN EL INDEX
            Empleado::destroy($id);
        }

        //DESPUES DEL BORRADO SE REDIRECCIONA AL INDEX EMPLEADO Y SE LE MANDA UN MENSAJE DE CONFIRMACION
        return redirect('empleado')->with('mensaje', 'Empleado Borrado');
    }
}