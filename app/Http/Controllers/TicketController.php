<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query("perPage", 10);
        $page = (int) $request->query("page", 0);
        $offset = $page * $perPage;

        $tickets = Ticket::skip($offset)->take($perPage)->get();

        return response()->json($tickets, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'customer_id' => 'required|exists:customer,id'
            ], [
                'title.required' => 'El titulo del ticket es obligatorio',
                'title.string' => 'El titulo del ticket debe ser una cadena de texto',
                'title.max' => 'La longitud del titulo no puede superar los 255 carácteres',
                'description.required' => 'La descripción del ticket es obligatoria',
                'description.string' => 'La descripción del ticket debe ser una cadena de texto',
                'description.max' => 'La longitud de la descripción no puede superar los 2000 carácteres',
                'customer_id.required' => 'El id del cliente es obligatorio',
                'customer_id.exists' => 'El cliente asociado al ticket debe existir'
            ]);
            $ticket = Ticket::create($validated);
            return response()->json($ticket, Response::HTTP_CREATED);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(string $id)
    {
        $ticket = Ticket::where('id', $id)->get();
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        try {
            $validated = $request->validated();
            $ticket->update($validated);

            return response()->json(["Success" => "Ticket actualizado con exito", "Ticket" => $ticket], Response::HTTP_OK);
        } catch (Exception $err) {
            return response()->json(["Error de validacion", $err->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(["Success" => "Ticket eliminado con exito"]);
    }

    public function customerTickets(Request $request, string $customer_id)
    {
        $perPage = (int) $request->query("perPage", 10);
        $page = (int) $request->query("page", 0);
        $total = (int) Ticket::where('customer_id', $customer_id)->count("id");
        $offset = $page * $perPage;

        $tickets = Ticket::where('customer_id', $customer_id)->skip($offset)->take($perPage)->get();
        $pagination = array("page"=>$page, "perPage"=>$perPage, "total"=>$total);

        return response()->json(["data" => $tickets, "meta" => $pagination], Response::HTTP_OK);
    }
}
