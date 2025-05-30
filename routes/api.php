<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactEmailController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\TokenCheck;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::apiResource("/customer", CustomerController::class);

Route::get('/tickets/newTickets', [TicketController::class, 'newTickets'])
    ->middleware(["jwt.auth", TokenCheck::class]);

Route::get('/customers/{customer_id}/tickets', [TicketController::class, 'customerTickets'])
    ->middleware(["jwt.auth", TokenCheck::class]);

Route::apiResource("/tickets", TicketController::class)
    ->middleware(["jwt.auth", TokenCheck::class]);

Route::get('/customers/{customer_id}/invoices', [InvoiceController::class, 'customerInvoices'])
    ->middleware(["jwt.auth", TokenCheck::class]);

Route::apiResource("/invoices", InvoiceController::class)
    ->middleware(["jwt.auth", TokenCheck::class]);

Route::post('/contact', [ContactEmailController::class, 'send']);
