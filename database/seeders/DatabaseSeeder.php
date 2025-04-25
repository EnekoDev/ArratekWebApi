<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Manteinance;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(ManteinanceSeeder::class);

        Manteinance::all()->each(function ($manteinance) {
            Customer::factory(2)->create(["manteinance_id" => $manteinance->id]);
        });

        Customer::all()->each(function ($customer) {
            Invoice::factory(3)->create(["customer_id" => $customer->id]);
        });

        Invoice::all()->each(function ($invoice) {
            Ticket::factory(5)->create(["invoice_id" => $invoice->id]);
        });
    }
}
