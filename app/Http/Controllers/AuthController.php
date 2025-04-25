<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    protected function respondWithToken($token) {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()
        ]);
    }

    public function login(LoginRequest $request) {
        $validatedData = $request->validated();
        $credentials = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Credenciales incorrectas'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $err) {
            return response()->json([
                'error' => 'No se pudo crear el token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->respondWithToken($token);
    }

    public function logout() {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
        } catch (JWTException $err) {
            return response()->json([
                'error' => 'No se pudo cerrar la sesión'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
