<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PagoVendedorController;
use App\Http\Controllers\RolController;
use App\Models\WhatsApp;

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

    Route::get('/paquetes/{paquete}', function ($paquete) {
        return ('Este es el paquete: ' . $paquete);
    });

    //Rutas para paquetes
    Route::get('/paquetes/{paquete}/edit', [PaqueteController::class, 'edit'])
        ->name('paquetes.edit'); // Acceder al formulario edit
    Route::get('/paquetes', [PaqueteController::class,  'index'])
        ->name('paquetes.paquetes'); //Mostrar Paquetes
    Route::post('/paquetes', [PaqueteController::class, 'store'])
        ->name('paquetes.store'); //Agregar Paquetes
    Route::put('paquetes/{paquete}',  [PaqueteController::class, 'update'])
        ->name("paquetes.update"); //Editar Paquetes
    Route::delete('paquetes/{paquete}', [PaqueteController::class, 'destroy'])
        ->name('paquetes.destroy'); //Eliminar Paquetes

    //Ruta para clientes
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])
        ->name('clientes.edit'); //Acceder a la página de editar
    Route::get('/clientes',        [ClienteController::class, 'index'])
        ->name('clientes.index'); //Mostrar Clientes
    Route::post('clientes',        [ClienteController::class, 'store'])
        ->name('clientes.store'); //Agregar Cliente
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])
        ->name('clientes.update'); //Editar Cliente
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])
        ->name('clientes.destroy'); //Eliminar Cliente

    //Ruta para el calendario
    Route::get('calendar/index', [CalendarController::class, 'index'])
        ->name('calendar.index')
        ->middleware('checkRole:admin,asesor');
    Route::post('/calendar', [CalendarController::class, 'store'])
        ->name('calendar.store')
        ->middleware('checkRole:admin');
    Route::patch('calendar/update/{id}', [CalendarController::class, 'update'])
        ->name('calendar.update');
    Route::delete('calendar/destroy/{id}', [CalendarController::class, 'destroy'])
        ->name('calendar.destroy');

    //Rutas para los vendendores
    Route::get('vendedor/index', [VendedorController::class, 'index'])
        ->name('vendedor.index');
    Route::post('/vendedor', [VendedorController::class, 'store'])
        ->name('vendedor.store');
    Route::get('vendedor/agregar/{contrato}', [VendedorController::class, 'addContrato'])
        ->name('vendedor.agregar');
    Route::get('/vendedor/{vendedor}/edit', [VendedorController::class, 'edit'])
        ->name('vendedor.edit');
    Route::get('/vendedor/{vendedorId}/datosVendedor', [VendedorController::class, 'datosVendedor'])
        ->name('vendedor.datos_vendedor');
    Route::get('/vendedor/pagos_pendiente', [VendedorController::class, 'pagosPendientes'])
        ->name('vendedores.pagosPendientes');
    Route::put('/vendedor/{vendedor}/update', [VendedorController::class, 'update'])
        ->name('vendedor.update');

    //Rutas para Pagos de Vendedores 
    Route::get('pagoVendedor/{pagoVendedor}/editar', [PagoVendedorController::class, 'edit'])
        ->name('pagoVendedor.edit');
    Route::put('pagoVendedor/{pago}', [PagoVendedorController::class, 'update'])
        ->name('pagoVendedor.update');
    Route::get('pagoVendedores/pagoRealizado/{pago}', [PagoVendedorController::class, 'pagado'])
        ->name('pagoVendedor.pagar');
    Route::get('pagoVendedores/revertirPago/{pago}', [PagoVendedorController::class, 'quitarPagado'])
        ->name('pagoVendedor.revertirPago');

    //Rutas para los contratos y clientes
    Route::get('/contrato/index/', [ContratoController::class, 'index'])->name('contrato.index');
    Route::get('contrato/agregar/{cliente}', [ContratoController::class, 'add_contrato'])
        ->name('contrato.agregar');
    Route::post('/contrato', [ContratoController::class, 'store'])
        ->name('contrato.store');
    Route::get('contratos/{contratoId}/addVendedor', [ContratoController::class, 'add_vendedor'])
        ->name('contrato.vendedores');
    Route::post('/contrato_vendedores', [ContratoController::class, 'add_vendedores_DB'])
        ->name('contrato.add_vendedores');


    Route::get('chat', [WhatsAppController::class, 'index'])
        ->name('chat.chat');
    Route::get('/enviaWpp', [WhatsAppController::class, 'enviarPHP'])
        ->name('chat.envia');
    Route::post('chat/obtenerMensajes', [WhatsappController::class, 'recibe'])
        ->name('chat.recibe');
    //Chat WhatsApp
    // Route::prefix('whatsapp')->group(function () {
    //     Route::post('enviar', [WhatsAppController::class,'enviar'])->name('whatsapp.enviar');
    Route::get('webhook', [WhatsAppController::class, 'webhook'])->name('whatsapp.webhook');
    //Route::post('/webhook', [WhatsAppController::class, 'recibir'])->name('whatsapp.recibir');
    // });

    Route::get('chat', [WhatsAppController::class, 'index']);

    // routes/web.php
    Route::get('/cliente/{id}', [ContratoController::class, 'obtenerDetallesCliente']);

    // rutas de roles
    Route::get('/rol', [RolController::class, 'index'])->name('roles.rol')->middleware('checkRole:admin')
    ->middleware('checkRole:admin');
    Route::put('/roles/{user}', [RolController::class, 'asignarRol'])->name('roles.asignar-rol')
    ->middleware('checkRole:admin');

});

require __DIR__ . '/auth.php';
