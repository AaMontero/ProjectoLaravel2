<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PagoVendedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\User_actions;
use App\Http\Controllers\UserActionsController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\UserAction;

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


//Route::post();
//Route::put();
//Route::delete();


//Middleware - Bloque de código que se ejecuta en el medio del enrutamiento

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');


    //RUTAS PARA PAQUETES
    Route::get('/paquetes/{paquete}/edit', [PaqueteController::class, 'edit'])
        ->name('paquetes.edit') // Formulario para edigar los paquetes
        ->middleware('checkRole:Administrador,superAdmin');
    Route::get('/paquetes', [PaqueteController::class,  'index'])
        ->name('paquetes.paquetes') //Mostrar Paquetes (Principal)
        ->middleware('checkRole:Administrador,Asesor,superAdmin');
    Route::post('/paquetes', [PaqueteController::class, 'store'])
        ->name('paquetes.store') //Agregar Paquetes
        ->middleware('checkRole:Administrador,superAdmin,Host');
    Route::put('paquetes/{paquete}',  [PaqueteController::class, 'update'])
        ->name("paquetes.update"); //Registra una actualización 
    Route::delete('paquetes/{paquete}', [PaqueteController::class, 'destroy'])
        ->name('paquetes.destroy') //Eliminar Paquetes
        ->middleware('checkRole:Administrador,superAdmin');

    //RUTAS PARA CLIENTES
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])
        ->name('clientes.edit'); //Formulario para editar un cliente
    Route::get('/clientes',        [ClienteController::class, 'index'])
        ->name('clientes.index'); //Mostrar Clientes (Principal)
    Route::post('clientes',        [ClienteController::class, 'store'])
        ->name('clientes.store'); //Registrar un cliente nuevo
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])
        ->name('clientes.update'); //Registrar la actualizacion de un cliente


    //RUTAS PARA EL CALENDARIO
    Route::get('calendar/index', [CalendarController::class, 'index'])
        ->name('calendar.index')
        ->middleware('checkRole:Administrador,superAdmin');
    Route::post('/calendar', [CalendarController::class, 'store'])
        ->name('calendar.store')
        ->middleware('checkRole:Administrador,superAdmin');
    Route::patch('calendar/update/{id}', [CalendarController::class, 'update'])
        ->name('calendar.update');
    Route::delete('calendar/destroy/{id}', [CalendarController::class, 'destroy'])
        ->name('calendar.destroy');




    //RUTAS PARA LOS VENDEDORES
    Route::get('vendedor/index', [VendedorController::class, 'index'])
        ->name('vendedor.index')
        ->middleware('checkRole:Administrador,superAdmin'); //Mostrar los vendedores (Principal) //Admin
    Route::post('/vendedor', [VendedorController::class, 'store'])
        ->name('vendedor.store'); //Agregar un nuevo vendedor
    Route::get('/vendedor/{vendedor}/edit', [VendedorController::class, 'edit'])
        ->name('vendedor.edit'); // Formulario para editar un vendedor
    Route::get('/vendedor/{vendedorId}/datosVendedor', [VendedorController::class, 'datosVendedor'])
        ->name('vendedor.datos_vendedor') // Ver los datos de un vendedor
        ->middleware('checkRole:Administrador,Asesor,superAdmin');
    Route::get('/vendedor/pagos_pendiente', [VendedorController::class, 'pagosPendientes'])
        ->name('vendedores.pagosPendientes')
        ->middleware('checkRole:Administrador,superAdmin'); //Pagos pendientes - Solo Admin
    Route::put('/vendedor/{vendedor}/update', [VendedorController::class, 'update'])
        ->name('vendedor.update'); // Actualizar los datos de un vendedor
    Route::get('/vendedor/{vendedor}/inactivo', [VendedorController::class, 'cambiarActivo'])
        ->name('vendedor.cambiarActivo'); // Cambiar el estado del vendedor


    // RUTAS PARA EL PAGO VENDEDORES
    Route::get('pagoVendedor/{pagoVendedor}/editar', [PagoVendedorController::class, 'edit'])
        ->name('pagoVendedor.edit'); // Formulario para editar un pago 
    Route::put('pagoVendedor/{pago}', [PagoVendedorController::class, 'update'])
        ->name('pagoVendedor.update'); //Actualizar un pago 
    Route::get('pagoVendedores/pagoRealizado/{pago}', [PagoVendedorController::class, 'pagado'])
        ->name('pagoVendedor.pagar'); //Cambia el estado de un pago a pago
    Route::get('pagoVendedores/revertirPago/{pago}', [PagoVendedorController::class, 'quitarPagado'])
        ->name('pagoVendedor.revertirPago'); //Cambia el estado de un pago a pendiente
    Route::get('pagoVendedores/{idVendedor}', [PagoVendedorController::class, 'pagarVendedor'])
        ->name('pagoVendedores.pagoVendedor');


    //RUTAS PARA CONTRATOS
    Route::get('/contrato/index', [ContratoController::class, 'index'])
        ->name('contrato.index'); // Mostrar los contratos (Principal)
    Route::get('contrato/agregar/{cliente}', [ContratoController::class, 'add_contrato'])
        ->name('contrato.agregar');  //Agregar Contrato desde un cliente 
    Route::post('/contrato', [ContratoController::class, 'store'])
        ->name('contrato.store'); //Registrar un nuevo contrato
    Route::get('contratos/{contratoId}/addVendedor', [ContratoController::class, 'add_vendedor'])
        ->name('contrato.vendedores'); //Agregar los vendedores al contrato (JS,C1,C2,V) 
    Route::post('/contrato_vendedores', [ContratoController::class, 'add_vendedores_DB'])
        ->name('contrato.add_vendedores'); //Registrar los vendedores en el contrato 



    //RUTAS PARA EL CHAT - NOTIFICACIONES 
    Route::get('chat', [WhatsAppController::class, 'index'])
        ->name('chat.chat'); //Mostrar el chat (Principal)
    Route::get('/enviaWpp', [WhatsAppController::class, 'enviarPHP'])
        ->name('chat.envia'); //Envía los mensajes
    Route::post('chat/obtenerMensajes', [WhatsappController::class, 'recibe'])
        ->name('chat.recibe'); //Recibe los mensajes
    Route::get('visto/{idchat}', [WhatsAppController::class, 'leerMensajesUsuario'])
        ->name('chat.leer_mensajes'); //Marcar los mensajes de un usuario como leidos


    // RUTAS DE ROLES 
    Route::get('/rol', [RolController::class, 'index'])
        ->name('roles.rol')
        ->middleware('checkRole:Administrador,superAdmin'); // Mostrar los roles 
    Route::put('/roles/{user}', [RolController::class, 'asignarRol'])
        ->name('roles.asignar-rol')
        ->middleware('checkRole:superAdmin'); //Cambiar el rol de un usuario 



    //RUTAS DE LOGS
    Route::post('save_task', [PusherPruebaControlller::class, 'save_task']); //Registrar un LOG
    Route::get('/log', [UserActionsController::class, 'index'])
        ->name('log')
        ->middleware('checkRole:superAdmin');



    // RUTAS DE TERMINOS Y CONDICIONES 
    Route::get('/politicas-privacidad', function () {
        return view('layouts.politicas');
    })->name('politicas');
    Route::get('/terminos-condiciones', function () {
        return view('layouts.terminos');
    })->name('terminos');


    //Notificaciones 
    Route::get('/cambiarEstadoNot/{notificacion}', [NotificacionController::class, 'leido'])
        ->name('notificacion.marcar_leido'); // Marcar como leida una notificaciòn. 
});



// RUTAS DE WEBHOOKS 
// Autentifiación para conectarse con APIS (No necesita estar logeado)
Route::get('webhook/recibe', [WhatsAppController::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('webhook/recibe', [WhatsAppController::class, 'recibe'])
    ->withoutMiddleware([VerifyCsrfToken::class]);



//RUTAS PARA TERMINOS Y CONDICIONES 
Route::get('/politicas-privacidad', function () {
    return view('layouts.politicas');
})->name('politicas');
Route::get('/terminos-condiciones', function () {
    return view('layouts.terminos');
})->name('terminos');

require __DIR__ . '/auth.php';
