<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\UnidadArticulo;
use Illuminate\Support\Str;

class UnidadArticuloServices{

    public function createCodeBar(){

        //Generamos un codigo aleatorio para el codigo de barra
        $codigoAleatorio = Str::random(8);
        
        //verificamos si el codigo existe en BD
        $is_exist = UnidadArticulo::where('codigo_barra', $codigoAleatorio)->first();

        //Si no existe devolvemos el codigo
        if(!$is_exist) return $codigoAleatorio;

        //Ejecutamos recursividad
        $this->createCodeBar();
    }


    public function store($items){

        $unidad_articulos = [];

        foreach($items as $item){
            
            for ($i=0; $i < $item->cantidad ; $i++) {     
                //Creamos la unidad
                $codeBar = $this->createCodeBar();

                $unidad = UnidadArticulo::create([
                    'articulo_id' => $item->articulo_id,
                    'estado' => 'disponible',
                    'factura_id' => $item->factura_id,
                    'codigo_barra' => $codeBar
                ]);

                array_push($unidad_articulos,$unidad);
            }
        }
        return $unidad_articulos;
    }

}