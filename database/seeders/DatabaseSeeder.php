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
        $customers = Customer::factory(10)->create();

        User::factory()->create([
            'email' => 'eneko@arratek.com',
            'password' => bcrypt('prueba123'),
            'admin' => true
        ]);

        $customers->each(function ($customer) {
            User::factory()->create([
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'admin' => false,
                'customer_id' => $customer->id,
            ]);

            $invoices = Invoice::factory(1)->create(['customer_id' => $customer->id]);

            $invoices->each(function ($invoice) {
                Ticket::factory(3)->create(['invoice_id' => $invoice->id]);
            });
        });
    }
}
