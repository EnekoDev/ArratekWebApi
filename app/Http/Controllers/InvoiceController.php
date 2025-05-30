<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminCheck;
use App\Models\Invoice;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validated = $request->validate([
                'perPage' => 'integer|min:10|max:100',
                'page' => 'integer|min:0',
            ], [
                'perPage.integer' => 'El valor por pagina debe ser númerico',
                'perPage.min' => 'El valor por pagina debe ser mínimo 10',
                'perPage.max' => 'El valor por pagina debe ser máximo 100',
                'page.integer' => 'La pagina debe ser númerica',
                'page.min' => 'La pagina debe ser mínimo 0',
            ]);

            $perPage = $validated['perPage'] ?? 10;
            $page = $validated['page'] ?? 0;
            $offset = $page * $perPage;
            $total = (int) Invoice::count("id");
            $pagination = array("page"=>$page, "perPage"=>$perPage, "total"=>$total);

            $invoices = Invoice::skip($offset)->take($perPage)->get();

            return response()->json(["data" => $invoices, "meta" => $pagination], Response::HTTP_OK);
        }  catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'number' => 'required|string|max:20',
                'date' => 'required|string|max:20',
                'total_time' => 'required|integer',
                'total_price' => 'required|numeric',
                'tax' => 'required|numeric',
                'customer_id' => 'required|exists:customers,id'
            ], [
                'number.required' => 'El número de factura es obligatorio',
                'number.string' => 'El número de factura debe ser una cadena de texto',
                'number.max' => 'La longitud del número no puede superar los 20 carácteres',
                'date.required' => 'La fecha de la factura es obligatoria',
                'date.string' => 'La fecha de la factura debe ser una cadena de texto',
                'date.max' => 'La longitud de la fecha no puede superar los 20 carácteres',
                'total_time.required' => 'El tiempo total es obligatorio',
                'total_time.integer' => 'El tiempo total debe ser un número entero',
                'total_price.required' => 'El precio total es obligatorio',
                'total_price.numeric' => 'El precio total debe ser un número',
                'tax.required' => 'El impuesto es obligatorio',
                'tax.numeric' => 'El impuesto debe ser un número',
                'customer_id.required' => 'El id del cliente es obligatorio',
                'customer_id.exists' => 'El cliente asociado a la factura debe existir'
            ]);
            $invoice = Invoice::create($validated);
            return response()->json($invoice, Response::HTTP_CREATED);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::where('id', $id)->get();
        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        try {
            $validated = $request->validate([
                'number' => 'string|max:20',
                'date' => 'string|max:20',
                'total_time' => 'integer',
                'total_price' => 'numeric',
                'tax' => 'numeric',
                'customer_id' => 'exists:customers,id'
            ], [
                'number.string' => 'El número de factura debe ser una cadena de texto',
                'number.max' => 'La longitud del número no puede superar los 20 carácteres',
                'date.string' => 'La fecha de la factura debe ser una cadena de texto',
                'date.max' => 'La longitud de la fecha no puede superar los 20 carácteres',
                'total_time.integer' => 'El tiempo total debe ser un número entero',
                'total_price.numeric' => 'El precio total debe ser un número',
                'tax.numeric' => 'El impuesto debe ser un número',
                'customer_id.exists' => 'El cliente asociado a la factura debe existir'
            ]);
            $invoice->update($validated);

            return response()->json(["Success" => "Factura actualizada con exito", "Factura" => $invoice], Response::HTTP_OK);
        } catch (Exception $err) {
            return response()->json(["Error de validacion", $err->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(["Success" => "Factura eliminada con exito"]);
    }

    public function customerInvoices(Request $request, string $customer_id)
    {
        try {
            $validated = $request->validate([
                'perPage' => 'integer|min:10|max:100',
                'page' => 'integer|min:0',
            ], [
                'perPage.integer' => 'El valor por pagina debe ser númerico',
                'perPage.min' => 'El valor por pagina debe ser mínimo 10',
                'perPage.max' => 'El valor por pagina debe ser máximo 100',
                'page.integer' => 'La pagina debe ser númerica',
                'page.min' => 'La pagina debe ser mínimo 0',
            ]);

            $perPage = $validated['perPage'] ?? 10;
            $page = $validated['page'] ?? 0;
            $offset = $page * $perPage;
            $total = (int) Invoice::where('customer_id', $customer_id)->count("id");

            $invoices = Invoice::where('customer_id', $customer_id)->skip($offset)->take($perPage)->get();
            $pagination = ["page" => $page, "perPage" => $perPage, "total" => $total];

            return response()->json(["data" => $invoices, "meta" => $pagination], Response::HTTP_OK);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
