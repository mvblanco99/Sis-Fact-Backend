<?php

namespace App\Traits;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

trait ExecuteQuery {

    /**
     * Ejecuta una consulta.
     *
     * @param callable $func Función que contiene la lógica de la consulta.
     * @param string $nameTable Nombre de la tabla para mensajes de error.
     * @param mixed $request Datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse
     */

    public function executeQuery(callable $func, string $nameTable="", $request = null):\Illuminate\Http\JsonResponse{

        try {
            //ejecutamos la funcion
            $response = $func($request);

            //Retornamos la respuesta
            return $response;
        } catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al actualizar ' . $nameTable . ' : ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }

    }


    /**
     * Ejecuta una consulta dentro de una transacción.
     *
     * @param callable $func Función que contiene la lógica de la consulta.
     * @param string $nameTable Nombre de la tabla para mensajes de error.
     * @param mixed $request Datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse
     */

     public function executeQueryTransaction(callable $func, string $nameTable="", $request = null):\Illuminate\Http\JsonResponse{

        // Empezar una transacción
        DB::beginTransaction();

        try {
            //ejecutamos la funcion
            $response = $func($request);

            // Commit de la transacción si todo ha ido bien
            DB::commit();

            //Retornamos la respuesta
            return $response;
        } catch (QueryException $e) {
            // Rollback de la transacción en caso de error
            DB::rollBack();
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al actualizar ' . $nameTable . ' : ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }

    }

}