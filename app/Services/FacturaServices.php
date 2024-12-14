<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\ItemFactura;

class FacturaServices{

    public function crearFactura($request) {
        //Creamos la factura
        $fact = Factura::create([
            'nota_entrega' => $request->nota_entrega,
            'fec_emis' => $request->fec_emis,
            'fec_vcto' => $request->fec_vcto,
            'empresa' => $request->empresa,
            'total_factura' => $request->total_factura,
            'user_id' => $request->user_id,
            'procesada' => Factura::NOPROCESADA
        ]);
        

        $items = [];
        
        //Creamos los items de la factura
        foreach ($request->items_factura as $item) {
            $i = ItemFactura::create([
                "cantidad" => $item["cantidad"],
                "articulo_id" => $item["articulo_id"],
                "factura_id" => $fact->id
            ]);
            array_push($items, $i);
        }
    
        return ["fact" => $fact, "items" => $items];
    }
}

