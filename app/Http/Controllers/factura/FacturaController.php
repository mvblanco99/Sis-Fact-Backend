<?php

namespace App\Http\Controllers\factura;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacturaRequest;
use App\Models\Factura;
use App\Traits\ExecuteQuery;
use App\Services\FacturaServices;

class FacturaController extends Controller
{
   use ExecuteQuery;
   protected $facturaService;

   public function __construct(FacturaServices $facturaServices){
    $this->facturaService = $facturaServices;
   }
   
    public function view(){
        $facturas = Factura::all();
        return response()->json(['facturas' => $facturas], 200);
    }

    public function store(FacturaRequest $request){ 
        $request->validated();

        //Verificamos que el numero de entrega no se repita
        $factura = Factura::where('nota_entrega',$request->nota_entrega)->first();

        if(!$factura){

            $response = $this->executeQueryTransaction(function($request){
                $responseService = $this->facturaService->crearFactura($request);

                //Retornamos la nueva categoria
                return response()->json([
                    'message' => 'Factura creada exitosamente.',
                    "Factura" => $responseService['fact'], 
                    "Items" => $responseService['items']
                ],201);
                
            },"Factura", $request);

            return $response;

        }else{
            return response()->json(['message' => 'El numero de entrega ya se encuentra registrado'],409);
        }

    }




}
