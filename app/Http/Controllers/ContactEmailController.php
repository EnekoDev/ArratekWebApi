<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class ContactEmailController extends Controller
{
    public function send(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'string|max:20',
                'message' => 'required|string|max:2000'
            ], [
                'name.required' => 'El nombre es obligatorio',
                'name.string' => 'El nombre debe ser una cadena de texto',
                'name.max' => 'La longitud del nombre no puede superar los 255 carácteres',
                'email.required' => 'El email es obligatorio',
                'email.email' => 'El email debe ser formato correcto',
                'email.max' => 'La longitud del email no puede superar los 255 carácteres',
                'phone.string' => 'El telefono debe ser una cadena de texto',
                'phone.max' => 'La longitud del telefono no puede superar los 20 carácteres',
                'message.required' => 'El mensaje es obligatorio',
                'message.string' => 'El mensaje debe ser una cadena de texto',
                'message.max' => 'La longitud del mensaje no puede superar los 2000 carácteres'
            ]);

            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'message' => $request->message
            ];
            Mail::to($request->email)->send(new ContactEmail($data));

            return response()->json(["Success" => "Email enviado con exito"], Response::HTTP_CREATED);
        } catch (Exception $err) {
            return response()->json(["Error" => $err->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
