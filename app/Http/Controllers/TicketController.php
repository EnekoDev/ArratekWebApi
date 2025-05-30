<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminCheck;
use App\Models\Ticket;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt.auth', AdminCheck::class])->only(['update']);
    }
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
            $total = (int) Ticket::count("id");
            $pagination = array("page"=>$page, "perPage"=>$perPage, "total"=>$total);

            $tickets = Ticket::skip($offset)->take($perPage)->get();

            return response()->json(["data" => $tickets, "meta" => $pagination], Response::HTTP_OK);
        }  catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'customer_id' => 'required|exists:customers,id'
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
            $validated = $request->validate([
                'answer' => 'required|string|max:2000'
            ]);
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

    public function newTickets(Request $request)
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
            $total = (int) Ticket::whereNull('answer')->count("id");

            $tickets = Ticket::whereNull('answer')->skip($offset)->take($perPage)->get();
            $pagination = array("page"=>$page, "perPage"=>$perPage, "total"=>$total);

            \Log::info('Offset: ' . $offset);
            \Log::info('Total: ' . $total);
            \Log::info('Tickets count: ' . $tickets->count());

            return response()->json(["data" => $tickets, "meta" => $pagination], Response::HTTP_OK);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function customerTickets(Request $request, string $customer_id)
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
            $total = (int) Ticket::where('customer_id', $customer_id)->count("id");

            $tickets = Ticket::where('customer_id', $customer_id)->skip($offset)->take($perPage)->get();
            $pagination = ["page" => $page, "perPage" => $perPage, "total" => $total];

            return response()->json(["data" => $tickets, "meta" => $pagination], Response::HTTP_OK);
        } catch (ValidationException $err) {
            return response()->json(["Error de validacion", $err->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
