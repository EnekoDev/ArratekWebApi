<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::apiResource("/customers", CustomerController::class);

Route::apiResource("/tickets", TicketController::class);
Route::get('/customers/{customer_id}/tickets', [TicketController::class, 'customerTickets']);
