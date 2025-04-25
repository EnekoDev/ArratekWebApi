<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request) {
        $perPage = (int) $request->query("perPage", 10);
        $page = (int) $request->query("page", 0);
        $offset = $page * $perPage;

        $customers = Customer::skip($offset)->take($perPage)->get();

        return response()->json($customers);
    }
}
