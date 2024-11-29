<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $uploadedImage = $this->uploadPhoto($request->file('image'));

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->image()->create([
            'path' => $uploadedImage
        ]);
        return $this->success([], 'User registered successfully', 201);
    }
    public function login(LoginRequest $request){

    }
    public function logout(Request $request){

    }
    public function getUser(Request $request){

    }
}
