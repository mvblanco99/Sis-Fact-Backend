<?php

namespace App\Http\Controllers\factura;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Models\UnidadArticulo;
use App\Traits\ExecuteQuery;
use App\Services\FacturaServices;
use App\Services\UnidadArticuloServices;

class FacturaController extends Controller
{
   use ExecuteQuery;
   protected $facturaService;
   protected $unidadArticulosService;

   public function __construct(FacturaServices $facturaServices, UnidadArticuloServices $unidadServices){
        $this->facturaService = $facturaServices;
        $this->unidadArticulosService = $unidadServices;
   }
   
    public function view(){
        $facturas = Factura::all();
        return response()->json(['facturas' => FacturaResource::collection($facturas)], 200);
    }

    public function store(FacturaRequest $request){ 
        $request->validated();

        //Verificamos que el numero de entrega no se repita
        $factura = Factura::where('nota_entrega',$request->nota_entrega)->first();

        if(!$factura){

            //Ejecutamos la query dentro de una transaccion
            $response = $this->executeQueryTransaction(function($request){
               
                //Creamos la factura y los items de la factura
                $responseService = $this->facturaService->crearFactura($request);

             
                
                $responseArticulosService = $request->tipo === Factura::RECARGA 
                    ?
                        //Actualizamos el estatus de las unidades existentes
                        $this->unidadArticulosService->updatedStatus($request->items_recarga)
                    :
                        //Creamos las unidades de los articulos
                        $this->unidadArticulosService->store($responseService['items']);

                
                //Actualizamos el status de procesado de la factura
                $responseService['fact']->procesada = 1;
                $responseService['fact']->save();

                $facturaUpdate = Factura::find($responseService['fact']->id);

                //Retornamos la nueva categoria
                return response()->json([
                    'message' => 'Factura creada exitosamente.',
                    'Factura' => $facturaUpdate, 
                    'Items' => $responseService['items'],
                    'Unidades Articulos' => $responseArticulosService
                ],201);
                
            },"Factura", $request);

            return $response;

        }else{
            return response()->json(['message' => 'El numero de entrega ya se encuentra registrado'],409);
        }
    }

    public function update(){
        return response()->json(['',200]);
    }

    public function delete(int $id){

        $factura = Factura::find($id);

        //Verificamos que la factura esta procesada
        if($factura->procesada == 1){   
            return response()->json(['message' => 'Error al eliminar factura procesada'],403);
        }
        
        $response = $this->executeQueryTransaction(function($request){
            $request->delete();
            return response()->json([
                'message' => 'Factura eliminada eliminada exitosamente.',
            ],200);

        },"Factura", $factura);

        return $response;
    }

    
}
