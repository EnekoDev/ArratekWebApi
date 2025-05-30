<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminCheck;
use App\Models\Invoice;
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
        $perPage = (int) $request->query("perPage", 10);
        $page = (int) $request->query("page", 0);
        $offset = $page * $perPage;

        $Invoices = Invoice::skip($offset)->take($perPage)->get();

        return response()->json($Invoices, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
