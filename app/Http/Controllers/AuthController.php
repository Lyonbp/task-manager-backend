<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return ApiResponse::success("Usuario registrado correctamente", [
            "user" => $user,
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");

        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::error("Credenciales incorrectas", null, 401);
        }

        return ApiResponse::success("Login exitoso", [
            "user" => auth()->user(),
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    // Perfil
    public function profile()
    {
        return ApiResponse::success("Perfil del usuario", auth()->user());
    }

    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ApiResponse::success("Sesión cerrada correctamente");
    }
}
