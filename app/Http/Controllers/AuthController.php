<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    public function register (UserRequest $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|max:255',
                'password' => 'required|string|min:8|max:255',
                'customer_id' => 'required|integer'
            ], [
                'email.required' => 'El email es obligatorio',
                'email.string' => 'El email debe ser una cadena de texto',
                'email.max' => 'La longitud del email no puede superar los 255 carácteres',
                'password.required' => 'La contraseña es obligatoria',
                'password.string' => 'La contraseña debe ser una cadena de texto',
                'password.min' => 'La longitud de la constraseña debe superar los 8 carácteres',
                'password.max' => 'La longitud de la constraseña no puede superar los 255 carácteres',
                'customer_id.required' => 'El id de cliente es obligatorio',
                'customer_id.integer' => 'El id de cliente debe ser un número'
            ]);
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'admin' => false,
                'customer_id' => $validatedData['customer_id']
            ]);

            return response()->json(['message' => 'Usuario registrado con exito'], Response::HTTP_CREATED);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    protected function respondWithToken($token) {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'customer_id' => auth()->user()->customer_id,
            'admin' => auth()->user()->admin
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
