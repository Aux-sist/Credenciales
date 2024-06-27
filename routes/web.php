<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\admin\PermisoController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RolController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [InicioController::class, 'index']);

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    Route::get('/permiso', [PermisoController::class, 'index'])->name('permiso');
    Route::get('/permiso/crear', [PermisoController::class, 'create'])->name('crear_permiso');
    //Rutas del menu//
    Route::get('menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/crear', [MenuController::class, 'create'])->name('crear_menu');
    Route::post('menu', [MenuController::class, 'store'])->name('guardar_menu');
    Route::post('menu/guardar-orden', [MenuController::class, 'guardarOrden'])->name('guardar_orden');
    //Rutas de Rol//
    Route::get('rol', [RolController::class, 'index'])->name('rol');
    Route::get('rol/crear', [RolController::class, 'create'])->name('crear_rol');
    Route::post('rol', [RolController::class, 'store'])->name('guardar_rol');
    Route::get('rol/{id}/editar', [RolController::class, 'edit'])->name('editar_rol');
    Route::put('rol/{id}', [RolController::class, 'update'])->name('actualizar_rol');
    Route::delete('rol/{id}', [RolController::class, 'destroy'])->name('eliminar_rol');
});