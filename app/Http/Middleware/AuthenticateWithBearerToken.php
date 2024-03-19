<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithBearerToken
{
    public function handle($request, Closure $next)
    {
        // Mendapatkan token dari header Authorization
        $token = $request->header('Authorization');

        // Jika token ditemukan
        if ($token) {
            // Mengatur pengguna berdasarkan token
            $user = Auth::guard('api')->setToken($token)->user();

            // Jika pengguna ditemukan, set pengguna yang terautentikasi
            if ($user) {
                Auth::guard('api')->setUser($user);
            }
        }

        return $next($request);
    }
}
