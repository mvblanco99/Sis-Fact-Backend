<?php

namespace App\Http\Controllers\articulo;

use App\Http\Controllers\Controller;
use App\Http\Requests\articulo\ArticuloRequest;
use App\Models\Articulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    public function view(){
        $articulos = Articulo::all();
        return response()->json(['articulos' => $articulos], 200);
    }

    public function store(ArticuloRequest $request){
        //Validamos los parametros
        $request->validated();

        //Buscamos un articulo con la misma descripcion
        $exist_articulo = Articulo::where('descripcion', $request->descripcion)->first();
        
        //Verificamos que la decripcion del articulo no este siendo usado
        if($exist_articulo){
            return response()->json(['message' => 'El articulo ya existe.'], 409);
        }

        try {
            //Creamos el articulo
            $articulo = Articulo::create([
                "descripcion" => $request->descripcion,
                "categoria_id" => $request->categoria_id
            ]);

            return response()->json(['message' => 'Articulo creado exitosamente.', 'articulo' => $articulo], 201);

        }catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al crear articulo: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function update(ArticuloRequest $request, int $id){
        $request->validated();

        //Buscamos el articulo
        $articulo = Articulo::find($id);


        //Verificamos que el articulo exista
        if($articulo){

            //Verificamos que la descripcion del articulo a modificar no este siendo usada
            $exist_descripcion_articulo = Articulo::where('descripcion', $request->descripcion)->first();
            if($exist_descripcion_articulo){
                return response()->json(['message' => 'Articulo ya existe.'], 409);
            }

            try {
                //Actualizamos el articulo
                $articulo->descripcion = $request->descripcion;
                $articulo->categoria_id = $request->categoria_id;
                $articulo->save();

                $articuloUpdated = Articulo::find($id);

                //Retornamos el articulo actualizado
                return response()->json([
                    'message' => 'Articulo actualizado exitosamente.',
                    'articulo' => $articuloUpdated
                ],200);

            } catch (QueryException $e) {
                // Manejar errores de consulta
                return response()->json(['error' => 'Error al actualizar el articulo: ' . $e->getMessage()], 500);
            } catch (\Exception $e) {
                // Manejar otros errores
                return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
            }

        }else{
            return response()->json(['message' => 'Articulo no existe.'], 404);
        }
    }

    public function delete(int $id){
        $articulo = Articulo::find($id);

        if($articulo){
            $articulo->delete();
            return response()->json([
                'message' => 'Articulo eliminado exitosamente.',
            ],200);
        }else{
            return response()->json(['message' => 'Articulo no existe.'], 404);
        }
    }
}
