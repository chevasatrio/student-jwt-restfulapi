<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {

    // POST /api/auth/register
    public function register(Request $request) {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => $user
        ], 201);
    }

    // POST /api/auth/login
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'success'    => true,
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user'       => Auth::user()
        ]);
    }

    // POST /api/auth/logout (requires token)
    public function logout() {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    // POST /api/auth/refresh (requires token)
    public function refresh() {
        return response()->json([
            'token'      => Auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    // GET /api/auth/me (requires token)
    public function me() {
        return response()->json([
            'success' => true,
            'user'    => auth()->user()
        ]);
    }
}
