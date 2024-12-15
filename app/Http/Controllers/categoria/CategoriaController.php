<?php

namespace App\Http\Controllers\categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\categoria\CategoriaRequest;
use App\Models\Categoria;
use App\Traits\ExecuteQuery;

class CategoriaController extends Controller
{
    use ExecuteQuery;

    public function view(){
        $categorias = Categoria::all();
        return response()->json(['categorias' => $categorias], 200);
    }

    public function store(CategoriaRequest $request){
        //Validamos los parametros
        $request->validated();

        //Buscamos una categoria con la misma descripcion
        $exist_categoria = Categoria::where('descripcion', $request->descripcion)->first();
        
        //Verificamos que el username no este siendo usado
        if($exist_categoria){
            return response()->json(['message' => 'Categoría ya existe.'], 409);
        }

        //Ejecutamos la query
        $response = $this->executeQuery(function($request){
               
            //Creamos la categoria
            $categoria = Categoria::create([
                "descripcion" => $request->descripcion
            ]);

            return response()->json(['message' => 'Categoría creada exitosamente.', 'categoria' => $categoria], 201);
            
        },"Categoria", $request);

        return $response;
    }

    public function update(CategoriaRequest $request, int $id){
        $request->validated();

        //Buscamos la categoria
        $categoria = Categoria::find($id);

        //Verificamos que la categoria exista
        if($categoria){

            $exist_descripcion_categoria = Categoria::where('descripcion', $request->descripcion)->first();
            
            //Verificamos que la descripcion de la categoria a modificar no este siendo usada
            if($exist_descripcion_categoria){
                return response()->json(['message' => 'Categoría ya existe.'], 409);
            }

            //Ejecutamos la query
            $response = $this->executeQuery(function($params){
                
                //Actualizamos la categoria
                $params['currentCategory']->descripcion = $params['request']->descripcion;
                $params['currentCategory']->save();

                $categoriaUpdated = Categoria::find($params['currentCategory']->id);

                //Retornamos la nueva categoria
                return response()->json([
                    'message' => 'Categoría actualizada exitosamente.',
                    'categoria' => $categoriaUpdated
                ],200);
                
            },
            "Categoria", 
            [
                'currentCategory' => $categoria, 
                'request' => $request
            ]);

            return $response;

        }else{
            return response()->json(['message' => 'Categoría no existe.'], 404);
        }
    }

    public function delete(int $id){
        $categoria = Categoria::find($id);

        if($categoria){

            //Ejecutamos la query
            $response = $this->executeQuery(function($request){
               
                //Eliminamos la categoria
                $request->delete();
                return response()->json([
                    'message' => 'Categoria eliminada exitosamente.',
                ],200);
            
            },"Categoria", $categoria);

            return $response;
        }else{
            return response()->json(['message' => 'Categoría no existe.'], 404);
        }
    }
}
