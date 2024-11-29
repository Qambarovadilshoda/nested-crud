<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return $this->error('User not found or password is incorrect', 404);
        }
        $token = $user->createToken($user->name)->plainTextToken;
        return $this->success($token, 'User logged successfully');
    }
    public function logout(Request $request){
        $this->deletePhoto($request->user()->image->path);
        $request->user()->tokens()->delete();
        return $this->success('User logged out successfully', 204);
    }
    public function getUser(Request $request){
        $user = $request->user();
        if(!$user){
            return $this->error('User not found', 404);
        }
        return $this->success(new UserResource($user));
    }
}
