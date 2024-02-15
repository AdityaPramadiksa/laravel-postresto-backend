<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Login api
    public function login(Request $request)
    {
        // validate request
        $request->validate([
            "email"=> "required|email",
            "password"=> "required"
            ]);

        // Check if the user exists
        $user = User::where("email",$request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message'=> 'User not found'
            ], 404);
        }

        // check if the password  is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message'=> 'Password is incorrect'
            ], 401);
        }

        // generate token
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token'=> $token,
            'user'=> $user
        ],200);
    }
}
