<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return response()->json([
            'msg' => "User has been created."
        ], 201);

    }

    public function login(LoginRequest $request) {
        if (Auth::attempt([
           'email' => $request->email,
           'password' => $request->password
        ])) {
            return response()->json([
                'msg' => 'User authenticated',
                'token' => auth()->user()->createToken('web')->plainTextToken
            ], 200);
        }

        return response()->json([
            'msg' => 'User not found.'
        ], 404);
    }

    public function logout(Request $request) {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'msg' => 'User has been logged out.'
        ], 200);
    }
}
