<?php

namespace App\Http\Controllers\salidas;

use App\Http\Controllers\Controller;
use App\Http\Requests\salidas\SalidasRequest;
use App\Models\Salida;
use App\Models\UnidadArticulo;
use App\Traits\ExecuteQuery;
use Illuminate\Http\Request;

class SalidasController extends Controller
{
    use ExecuteQuery;
    
    public function view(){
        $salidas = Salida::all();
        return response()->json(['salidas' => $salidas],200);
    }

    public function store(SalidasRequest $request){
        //Validamos los parametros
        $request->validated();

        //Verificamos que hayan articulos disponibles
        $unidad = UnidadArticulo::where('articulo_id', $request->articulo_id, 'estado','disponible');

        return response()->json(['message' => 'Departamento creada exitosamente.', 'departamento' => $unidad], 201);

        //Ejecutamos la query
        // $response = $this->executeQuery(function($request){
                
        //     //Creamos la categoria
        //     $departamento = Departamento::create([
        //         "descripcion" => $request->descripcion
        //     ]);
 
        //     return response()->json(['message' => 'Departamento creada exitosamente.', 'departamento' => $departamento], 201);
             
        // },"Departamento", $request);
 
        // return $response;
    }

}
