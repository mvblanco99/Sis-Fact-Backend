<?php

namespace App\Http\Controllers\unidadArticulo;

use App\Http\Controllers\Controller;
use App\Http\Requests\unidadArticulo\UnidadArticuloRequest;
use App\Models\UnidadArticulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UnidadArticuloController extends Controller
{
    
    public function store(UnidadArticuloRequest $request){
        //Validamos los parametros
        $request->validated();

        
        try {
            //Creamos el articulo
            $articulo = UnidadArticulo::create([
                'articulo_id' => $request->articulo_id,
                'codigo_barra' => $request->codigo_barra,
            ]);

            return response()->json(['message' => 'Unidad de Articulo creada exitosamente.', 'articulo' => $articulo], 201);

        }catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al crear unidad de articulo: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    // public function update(ArticuloRequest $request, int $id){
    //     $request->validated();

    //     //Buscamos el articulo
    //     $articulo = Articulo::find($id);


    //     //Verificamos que el articulo exista
    //     if($articulo){

    //         //Verificamos que la descripcion del articulo a modificar no este siendo usada
    //         $exist_descripcion_articulo = Articulo::where('descripcion', $request->descripcion)->first();
    //         if($exist_descripcion_articulo){
    //             return response()->json(['message' => 'Articulo ya existe.'], 409);
    //         }

    //         try {
    //             //Actualizamos el articulo
    //             $articulo->descripcion = $request->descripcion;
    //             $articulo->categoria_id = $request->categoria_id;
    //             $articulo->save();

    //             $articuloUpdated = Articulo::find($id);

    //             //Retornamos el articulo actualizado
    //             return response()->json([
    //                 'message' => 'Articulo actualizado exitosamente.',
    //                 'articulo' => $articuloUpdated
    //             ],200);

    //         } catch (QueryException $e) {
    //             // Manejar errores de consulta
    //             return response()->json(['error' => 'Error al actualizar el articulo: ' . $e->getMessage()], 500);
    //         } catch (\Exception $e) {
    //             // Manejar otros errores
    //             return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
    //         }

    //     }else{
    //         return response()->json(['message' => 'Articulo no existe.'], 404);
    //     }
    // }

    public function delete(int $id){
        $unidad_articulo = UnidadArticulo::find($id);

        if($unidad_articulo){
            $unidad_articulo->delete();
            return response()->json([
                'message' => 'Unidad de Articulo eliminada exitosamente.',
            ],200);
        }else{
            return response()->json(['message' => 'Unidad de Articulo no existe.'], 404);
        }
    }
}
