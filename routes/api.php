<?php

use App\Http\Controllers\articulo\ArticuloController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\categoria\CategoriaController;
use App\Http\Controllers\departamento\DepartamentoController;
use App\Http\Controllers\factura\FacturaController;
use App\Http\Controllers\salidas\SalidasController;
use App\Http\Controllers\unidadArticulo\UnidadArticuloController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Rutas para la autenticacion
Route::prefix('auth')->group(function () {
    //Login de usuarios
    Route::post('/login', [AuthController::class,'login']);
});

//Rutas protegidas
Route::middleware('auth:sanctum')->group(function(){
    
    //Cerrado de sesion de usuarios
    Route::get('auth/logout', [AuthController::class,'logout']);
    
    //Users
    Route::get('users', [UserController::class,'view']);
    Route::get('users/{id}', [UserController::class,'viewAUser']);
    Route::post('users/store', [AuthController::class,'store']);
    Route::delete('users/{id}', [UserController::class,'delete']);

    //Categorias
    Route::get('categoria', [CategoriaController::class,'view']);
    Route::post('categoria/store', [CategoriaController::class,'store']);
    Route::put('categoria/{id}', [CategoriaController::class,'update']);
    Route::delete('categoria/{id}', [CategoriaController::class,'delete']);
    
    //Articulos
    Route::get('articulo', [ArticuloController::class,'view']);
    Route::post('articulo/store', [ArticuloController::class,'store']);
    Route::put('articulo/{id}', [ArticuloController::class,'update']);
    Route::delete('articulo/{id}', [ArticuloController::class,'delete']);
    
    //Unidad Articulos
    Route::get('unidad_articulo/{codigo_barra}', [UnidadArticuloController::class,'view']);
    Route::post('unidad_articulo/store', [UnidadArticuloController::class,'store']);
    Route::put('unidad_articulo/{id}', [UnidadArticuloController::class,'update']);
    Route::delete('unidad_articulo/{id}', [UnidadArticuloController::class,'delete']);
    
    //Factura
    Route::get('factura', [FacturaController::class,'view']);
    Route::post('factura/store', [FacturaController::class,'store']);
    Route::put('factura/{id}', [FacturaController::class,'update']);
    Route::delete('factura/{id}', [FacturaController::class,'delete']);
    
    //Departamentos
    Route::get('departamento', [DepartamentoController::class,'view']);
    Route::post('departamento/store', [DepartamentoController::class,'store']);
    Route::put('departamento/{id}', [DepartamentoController::class,'update']);
    Route::get('departamento/{id}', [DepartamentoController::class,'viewADepartamento']);
    Route::delete('departamento/{id}', [DepartamentoController::class,'delete']);

    //Salidas
    Route::get('salidas', [SalidasController::class,'view']);
    Route::post('salidas/store', [SalidasController::class,'store']);
    Route::put('salidas/{id}', [SalidasController::class,'update']);
    Route::delete('salidas/{id}', [SalidasController::class,'delete']);

});
