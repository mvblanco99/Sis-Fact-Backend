<?php

namespace App\Http\Controllers\categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\categoria\CategoriaRequest;
use App\Models\Categoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    
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

        try {
            //Creamos la categoria
            $categoria = Categoria::create([
                "descripcion" => $request->descripcion
            ]);

            return response()->json(['message' => 'Categoría creada exitosamente.', 'categoria' => $categoria], 201);

        }catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al crear categoría: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function update(CategoriaRequest $request, int $id){
        $request->validated();

        //Buscamos la categoria
        $categoria = Categoria::find($id);


        //Verificamos que la categoria exista
        if($categoria){

            //Verificamos que la descripcion de la categoria a modificar no este siendo usada
            $exist_descripcion_categoria = Categoria::where('descripcion', $request->descripcion)->first();
            if($exist_descripcion_categoria){
                return response()->json(['message' => 'Categoría ya existe.'], 409);
            }

            try {
                //Actualizamos la categoria
                $categoria->descripcion = $request->descripcion;
                $categoria->save();

                $categoriaUpdated = Categoria::find($id);

                //Retornamos la nueva categoria
                return response()->json([
                    'message' => 'Categoría actualizada exitosamente.',
                    'categoria' => $categoriaUpdated
                ],200);

            } catch (QueryException $e) {
                // Manejar errores de consulta
                return response()->json(['error' => 'Error al actualizar categoría: ' . $e->getMessage()], 500);
            } catch (\Exception $e) {
                // Manejar otros errores
                return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
            }

        }else{
            return response()->json(['message' => 'Categoría no existe.'], 404);
        }
    }

    public function delete(int $id){
        $categoria = Categoria::find($id);

        if($categoria){
            $categoria->delete();
            return response()->json([
                'message' => 'Categoria eliminada exitosamente.',
            ],200);
        }else{
            return response()->json(['message' => 'Categoría no existe.'], 404);
        }
    }
}
