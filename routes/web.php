<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //nos redirige al login al cargar la pagina
    return view('auth.login');
});

/*COMENTAMOS ESTAS LINEAS YA QUE EN LA LINEA SIGUIENTE(29) SE HACE EL LLAMADO A TODAS LAS URL DE CADA METODO
Route::get('/empleado', function () {
    return view('empleado.index');
});

Route::get('empleado/create', [EmpleadoController::class, 'create']);
*/

//Route::resource('empleado', EmpleadoController::class) a esto debemos agregarle el middleware('auth) para que respete la autenticacion, ya que si esto si digitamos la url por ejemplo del create asi no estemos logeados nos dara acceso lo cual no es correcto
Route::resource('empleado', EmpleadoController::class)->middleware('auth');

//PARA ESTE EJEMPLO SOLO QUEREMOS QUE EXISTA UN USUARIO POR LO TANTO QUITAREMOS LOS ENLACES DE REGISTRAR Y DE OLVIDO PASSWORD
//Auth::routes();
//EN CORCHETES PONEMOS LO QUE QUEREMOS DESAPARECER
Auth::routes(['register'=>false, 'reset'=>false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// AHORA DEBEMOS CAMBIAR PARA QUE AL LOGEARSE MUESTRE LA VISTA PRINCIPAL DE EMPLEADO

Route::get('/home', [EmpleadoController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');
});