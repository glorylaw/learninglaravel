<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    //
    // Create the login function

    public function login(LoginRequest $request){
        $token = auth()->attempt($request->validated());
        
        if($token){
            return $this->responseWithToken($token, auth()->user());
         }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid Credentials'
            ], 401);
        }
    }

    //Create the registration method

    public function register(RegistrationRequest $request){
        $user = User::create($request->validated());
        if($user){
            $token = auth()->login($user);
            return $this->responseWithToken($token, $user);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occured while trying to create user'
            ],500);
        }
    }

    //Return Access token
    public function responseWithToken($token, $user){
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }
}
