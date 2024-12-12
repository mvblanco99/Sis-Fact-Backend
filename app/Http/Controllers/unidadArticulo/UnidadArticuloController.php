<?php

namespace App\Http\Controllers\unidadArticulo;

use App\Http\Controllers\Controller;
use App\Http\Requests\unidadArticulo\UnidadArticuloRequest;
use App\Http\Requests\unidadArticulo\UpdateUnidadArticuloRequest;
use App\Models\Articulo;
use App\Models\UnidadArticulo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;

class UnidadArticuloController extends Controller
{
    public function store(UnidadArticuloRequest $request){
        //Validamos los parametros
        $request->validated();

        $articulo = Articulo::find($request->articulo_id);

        //Generamos el codigo qr para esta unidad
        try {
            
            //Creamos la unidad
            $unidad = UnidadArticulo::create([
                "articulo_id" => $articulo->id,
                "estado" => "disponible" 
            ]);

            return response()->json(['message' => 'Unidad de Articulo creada exitosamente.', 'articulo' => $unidad], 201);

        }catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al crear unidad de articulo: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateUnidadArticuloRequest $request, int $id){
        $request->validated();

        //Buscamos el articulo
        $articulo = UnidadArticulo::find($id);

        //Verificamos que el articulo exista
        if($articulo){

            try {
                //Actualizamos el articulo
                $articulo->estado = $request->estado;
                $articulo->save();

                $articuloUpdated = UnidadArticulo::find($id);

                //Retornamos el articulo actualizado
                return response()->json([
                    'message' => 'Unidad de Articulo actualizado exitosamente.',
                    'articulo' => $articuloUpdated
                ],200);

            } catch (QueryException $e) {
                // Manejar errores de consulta
                return response()->json(['error' => 'Error al actualizar la unidad de articulo: ' . $e->getMessage()], 500);
            } catch (\Exception $e) {
                // Manejar otros errores
                return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
            }

        }else{
            return response()->json(['message' => 'Articulo no existe.'], 404);
        }
    }

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
