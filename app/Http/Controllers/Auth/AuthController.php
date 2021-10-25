<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        if (!auth()->user()->can('auth.register')){
            return response()->json([
                'msg' => "You haven't permission to perform action."
            ], 403);
        }
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
