<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\branch;
use App\Models\business;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    // public function register(RegistrationRequest $request){
    //     $user = User::create($request->validated());
    //     if($user){
    //         $token = auth()->login($user);
    //         return $this->responseWithToken($token, $user);
    //     }else{
    //         return response()->json([
    //             'status' => 'failed',
    //             'message' => 'An error occured while trying to create user'
    //         ],500);
    //     }
    // }


public function register(RegistrationRequest $request)
{
    
        DB::beginTransaction();

        $validatedData = $request->validated(); // This will automatically validate the request

        $branchId = 'QC_BR_' . strtoupper(Str::random(8));

        $businessId = 'QC_BS_' . strtoupper(Str::random(8));

        $userId = 'QC_US_' . strtoupper(Str::random(8));
       
        $freeEndDate = Carbon::now()->addDays(14);

        // Create Business
        $business = Business::create([
            'id' => $businessId,
            'admin_id' => $userId,
            'name' => $validatedData['business_name'],
            'country' => $validatedData['country'],
            'currency' => $validatedData['currency'],
        ]);


        // Create Branch
        $branch = Branch::create([
            'id' => $branchId,
            'business_id' => $businessId,
            'name' => 'Headquarter',
        ]);

        // Create Admin
        $admin = User::create([
            'id' => 'QC_US_' . $userId,
            'branch_id' => $branchId,
            'business_id' => 'QC_BS_' . $businessId,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'country' => $validatedData['country'],
            'currency' => $validatedData['currency'],
            'trial_period' => $freeEndDate
            
        ]);

        // Commit the transaction and return token
        DB::commit();
       // $token = $admin->createToken('authToken')->plainTextToken;
        $token = auth()->login($admin);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'token' => $token,
            'user' => $admin
        ], 201);

    
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
