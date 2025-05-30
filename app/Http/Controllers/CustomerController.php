<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TokenCheck;
use App\Models\Customer;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware(['jwt.auth', TokenCheck::class])->except(['store']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query("perPage", 10);
        $page = (int) $request->query("page", 0);
        $offset = $page * $perPage;

        $customers = Customer::skip($offset)->take($perPage)->get();

        return response()->json($customers, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:20'
            ], [
                'name.required' => 'El nombre del cliente es obligatorio',
                'name.string' => 'El nombre del cliente debe ser una cadena de texto',
                'name.max' => 'La longitud del nombre no puede superar los 255 carácteres',
                'address.required' => 'La direccion del cliente es obligatoria',
                'address.string' => 'La direccion del cliente debe ser una cadena de texto',
                'address.max' => 'La longitud de la direccion no puede superar los 255 carácteres',
                'phone.required' => 'El telefono del cliente es obligatorio',
                'phone.string' => 'El telefono del cliente debe ser una cadena de texto',
                'phone.max' => 'La longitud del telefono no puede superar los 20 carácteres'
            ]);
            $customer = Customer::create($validated);
            return response()->json($customer, Response::HTTP_CREATED);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
