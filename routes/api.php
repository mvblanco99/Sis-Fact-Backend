<?php

use App\Http\Controllers\articulo\ArticuloController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\categoria\CategoriaController;
use App\Http\Controllers\unidadArticulo\UnidadArticuloController;
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
    //Registro de usuarios
    Route::post('/register',[AuthController::class,'register']);
    //Login de usuarios
    Route::post('/login',[AuthController::class,'login']);
});

//Rutas protegidas
Route::middleware('auth:sanctum')->group(function(){
    
    //Cerrado de sesion de usuarios
    Route::get('auth/logout',[AuthController::class,'logout']);

    //Categorias
    Route::get('categoria', [CategoriaController::class,'view']);
    Route::post('categoria/store',[CategoriaController::class,'store']);
    Route::put('categoria/{id}',[CategoriaController::class,'update'] );
    Route::delete('categoria/{id}',[CategoriaController::class,'delete'] );

    //Articulos
    Route::get('articulo', [ArticuloController::class,'view']);
    Route::post('articulo/store',[ArticuloController::class,'store']);
    Route::put('articulo/{id}',[ArticuloController::class,'update'] );
    Route::delete('articulo/{id}',[ArticuloController::class,'delete'] );

    //Unidad Articulos
    Route::get('unidad_articulo', [UnidadArticuloController::class,'view']);
    Route::post('unidad_articulo/store',[UnidadArticuloController::class,'store']);
    Route::put('unidad_articulo/{id}',[UnidadArticuloController::class,'update'] );
    Route::delete('unidad_articulo/{id}',[UnidadArticuloController::class,'delete'] );


});
