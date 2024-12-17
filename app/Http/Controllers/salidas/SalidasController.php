<?php

namespace App\Http\Controllers\salidas;

use App\Http\Controllers\Controller;
use App\Http\Requests\salidas\SalidasRequest;
use App\Models\Salida;
use App\Models\UnidadArticulo;
use App\Traits\ExecuteQuery;
use Illuminate\Support\Facades\Auth;

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

        //Recuperamos el usuario logueado
        $user = Auth::user();

        /**
         * Falta manejar el cambio de estado de las unidades que se encuentran en uso, en caso de que el destinatario 
         * tenga una unidad en uso. En caso contrario, sencillamente creamos la salida y asignamos la nueva unidad 
         * al destinatario 
         */

        //Verificamos que el articulo esta disponible
        $unidad = UnidadArticulo::where('articulo_id', $request->articulo_id,)
            ->where('estado','disponible')
            ->whereNotNull('codigo_barra')
            ->first();

        if($unidad){

            //Ejecutamos la query dentro de una transaccion
            $response = $this->executeQueryTransaction(function($params){
                    
                //Creamos la salida
                $salida = Salida::create([
                    'cantidad' => $params['request']->cantidad,
                    'destinatario' => $params['request']->destinatario,
                    'motivo' => $params['request']->motivo,
                    'departamento_id' => $params['request']->departamento,
                    'user_id' => $params['user_logueado']->id,
                    'unidad_articulo_id' => $params['unidad']->id,
                    'fecha' => now()
                ]);

                $u = UnidadArticulo::find($salida->unidad_articulo_id);

                //Actualizamos el estado de la nueva unidad
                $u->estado = 'en uso';
                $u->save();

                $uUpdated = UnidadArticulo::find($salida->unidad_articulo_id);

                return response()->json(['message' => 'Salida creada exitosamente.', 'salida' => $salida, 'unidad' => $uUpdated], 201);
                    
            },"Salida", ['request' => $request, 'user_logueado' => $user, 'unidad' => $unidad]);

            return $response;

        }else{
            return response()->json(['message' => 'El articulo no tiene existencias disponibles'], 200);
        }  
    }

    public function delete(int $id){
        $salida = Salida::find($id);

        if($salida){

            //Ejecutamos la query dentro de una transaccion
            $response = $this->executeQueryTransaction(function($params){
               
                //Recuperamos la unidad del articulo asociada a la salida
                $u = UnidadArticulo::find($params->unidad_articulo_id);

                //Actualizamos el estado de la unidad del articulo asociada a la salida
                $u->estado = 'disponible';
                $u->save();

                //Eliminamos la salida
                $params->delete();
                return response()->json([
                    'message' => 'Salida eliminada exitosamente.',
                ],200);
            
            },"Salida", $salida);

            return $response;
        }else{
            return response()->json(['message' => 'Salida no existe.'], 404);
        }
    }
}
