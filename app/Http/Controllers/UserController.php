<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ExecuteQuery;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use ExecuteQuery;

    public function view(){
        $users = User::with('departamento')->get();
        return response()->json(['users' => UserResource::collection($users)],200);
    }

    public function viewAUser(int $id){
        $user = User::find($id);
        return response()->json(['user' => $user],200);
    }

    public function update(Request $request, int $id){
        //valdiamos los datos
        // $request->validated();

        return response()->json([$request->name],200);

        $user = User::find($id);

        if($user){

            //Ejecutamos la query
            $response = $this->executeQuery(function($params){
               
                //actualizamos el user
                $params['user']->name = $params['request']->name;
                $params['user']->departamento_id = $params['request']->departamento_id;
                $params['user']->save();

                //Recuperamos los datos del usuario actualizado
                $userUpdated = User::find($params['user']->id);

                return response()->json([
                    'message' => 'Usuario actualizado exitosamente.',
                    'user' => $userUpdated
                ],200);
            
            },"Usuario", ['user' => $user, 'request' => $request]);

            return $response;
        }else{
            return response()->json(['message' => 'Usuario no existe.'], 404);
        }
    }   

    public function delete(int $id){
        $user = User::find($id);

        if($user){

            //Ejecutamos la query
            $response = $this->executeQuery(function($request){
               
                //Eliminamos el departamento
                $request->delete();
                return response()->json([
                    'message' => 'Usuario eliminada exitosamente.',
                ],200);
            
            },"Usuario", $user);

            return $response;
        }else{
            return response()->json(['message' => 'Usuario no existe.'], 404);
        }
    }
}
