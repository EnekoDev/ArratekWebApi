<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'eneko@arratek.com',
            'password' => 'prueba123',
            'admin' => true
        ]);

        Customer::all()->each(function ($customer) {
            User::factory()->create([
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'admin' => false,
                'customer_id' => $customer->id,
            ]);
        });

        Customer::all()->each(function ($customer) {
            Invoice::factory(3)->create(["customer_id" => $customer->id]);
        });

        Invoice::all()->each(function ($invoice) {
            Ticket::factory(5)->create(["invoice_id" => $invoice->id]);
        });
    }
}
