<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    public function register (UserRequest $request) {
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password'])
        ]);

        return response()->json(['message' => 'Usuario registrado con exito'], Response::HTTP_CREATED);
    }
    protected function respondWithToken($token) {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'customer_id' => auth()->user()->customer_id
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
