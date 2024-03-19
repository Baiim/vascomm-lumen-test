<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
                'data' => null,
            ], 401);
        }
    
        // Autentikasi berhasil, buat token akses
        $token = $user->createToken('MyAppToken')->accessToken;
    
        return response()->json([
            'code' => 200,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ],
        ]);
    }
}
