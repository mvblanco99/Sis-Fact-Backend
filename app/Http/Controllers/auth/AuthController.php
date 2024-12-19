<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(LoginRequest $request){
        $request->validated();

        //Verificamos credenciales
        if(!Auth::attempt($request->only(['username','password']))){
            return response()->json([
                'message' => 'Credenciales Invalidas'
            ],401);
        }

        //Recuperamos el user
        $user = User::where('username', $request->username)->first();

        //Recuperamos el id del departamento de sistemas
        $dep = Departamento::where('descripcion','Sistemas')->first();
        
        //Esto lo puedo cambiar por una policy
        if($user->departamento_id !== $dep->id){
            return response()->json([
                'message' => 'No tiene privilegios para ingresar al sistema.'
            ],403);
        }

        //Enviamos en la respuesta el usuario logeado y los datos del usuario 
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'user' => $user
        ],200);

    }

    public function store(RegisterRequest $request){ 
        //Validamos los datos
        $request->validated();

        //Buscamos un usuario con el mismo username
        $exist_user = User::where('username', $request->username)->first();
        
        //Verificamos que el username no este siendo usado
        if($exist_user){
            return response()->json(['message' => 'Username ya se encuentra en uso.'], 409);
        }

        //Creamos el usuario
        try{
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'departamento_id' => $request->departamento,
            ]);

            return response()->json(['message' => 'Usuario creado exitosamente.', 'user' => $user], 201);
        }catch (QueryException $e) {
            // Manejar errores de consulta
            return response()->json(['error' => 'Error al crear el usuario: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }   

    public function logout(){
        //Recuperamos el usuario logueado
        $user = Auth::user();
        //Borramos todos los tokens de sesion
        $user->tokens()->delete();
        //Retonamos la respuesta
        return response()->json([], 204);
    }

    
}
