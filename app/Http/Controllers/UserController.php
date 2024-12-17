<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function view(){
        $users = User::with('departamento')->get();
        return response()->json(['users' => UserResource::collection($users)],200);
    }

    public function viewAUser(int $id){
        $user = User::find($id)->departamento;
        return response()->json(['user' => $user],200);
    }
}
