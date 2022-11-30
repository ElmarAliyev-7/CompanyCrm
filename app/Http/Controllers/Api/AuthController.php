<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authUser()
    {
        return response([
            'user' => request()->user()
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        if( !Auth::attempt( $request->only(['email','password']) ) ){
            return response([
                "message" => "Invalid credentials",
                "data" => null
            ],401);
        }

        $token = Auth::user()->createToken('token')->plainTextToken;

        return response([
            "message" => "Request succeeded",
            "data" => [
                "token" => $token
            ]
        ],200);
    }

    public function logout()
    {
        request()->user()->tokens()->find(request()->user()->currentAccessToken()->id)->delete();

        return response([
            'message' => 'Logged Out Successfully'
        ], 200);
    }

}
