<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\admin\PermisoController;
use App\Http\Controllers\Admin\MenuController;
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
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/crear', [MenuController::class, 'create'])->name('crear_menu');
    Route::post('menu', [MenuController::class, 'store'])->name('guardar_menu');
});