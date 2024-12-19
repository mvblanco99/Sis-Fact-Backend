<?php

namespace App\Http\Controllers\departamento;

use App\Http\Controllers\Controller;
use App\Http\Requests\departamento\DepartamentoRequest;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use App\Traits\ExecuteQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartamentoController extends Controller
{
    use ExecuteQuery;
    
    public function view(){
        $departamentos = Departamento::all();
        return response()->json(['departamentos' => DepartamentoResource::collection($departamentos)],200);
    }

    public function viewADepartamento(int $id){
        $departamento = Departamento::find($id);
        if($departamento){
            return response()->json(['departamento' => new DepartamentoResource($departamento)],200);
        }

        return response()->json(['error' => 'Departamento no encontrado'], Response::HTTP_NOT_FOUND);
    }

    public function store(DepartamentoRequest $request){
        //Validamos los parametros
        $request->validated();

        //Buscamos un departamento con la misma descripcion
        $exist_departamento = Departamento::where('descripcion', $request->descripcion)->first();
         
        //Verificamos que la descripcion del departamento no este siendo usado
        if($exist_departamento){
            return response()->json(['message' => 'Departamento ya existe.'], 409);
        }
 
        //Ejecutamos la query
        $response = $this->executeQuery(function($request){
                
            //Creamos la categoria
            $departamento = Departamento::create([
                "descripcion" => $request->descripcion
            ]);
 
            return response()->json(['message' => 'Departamento creada exitosamente.', 'departamento' => $departamento], 201);
             
        },"Departamento", $request);
 
        return $response;
    }

    public function update(Request $request, int $id){
        //$request->validated();
        return response()->json(['message' => $request->descripcion], 200);
        //Buscamos el departamento
        $departamento = Departamento::find($id);

        //Verificamos que el departamento exista
        if($departamento){

            $exist_descripcion_departamento = Departamento::where('descripcion', $request->descripcion)->first();
            
            //Verificamos que la descripcion del departamento a modificar no este siendo usada
            if($exist_descripcion_departamento){
                return response()->json(['message' => 'Departamento ya existe.'], 409);
            }

            //Ejecutamos la query
            $response = $this->executeQuery(function($params){
                
                //Actualizamos el departamento
                $params['currentDepartamento']->descripcion = $params['request']->descripcion;
                $params['currentDepartamento']->save();

                $departamentoUpdated = Departamento::find($params['currentDepartamento']->id);

                //Retornamos el nuevo departamento
                return response()->json([
                    'message' => 'Departamento actualizada exitosamente.',
                    'departamento' => $departamentoUpdated
                ],200);
                
            },
            "Departamento", 
            [
                'currentDepartamento' => $departamento, 
                'request' => $request
            ]);

            return $response;

        }else{
            return response()->json(['message' => 'Departamento no existe.'], 404);
        }
    }

    public function delete(int $id){
        $departamento = Departamento::find($id);

        if($departamento){

            //Ejecutamos la query
            $response = $this->executeQuery(function($request){
               
                //Eliminamos el departamento
                $request->delete();
                return response()->json([
                    'message' => 'Departamento eliminada exitosamente.',
                ],200);
            
            },"Departamento", $departamento);

            return $response;
        }else{
            return response()->json(['message' => 'Departamento no existe.'], 404);
        }
    }

}
