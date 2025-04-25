<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(12),
            'resolved_on' => $this->faker->date('Y-m-d'),
            'customer_id' => function () {
                return Customer::inRandomOrder()->first()?->id;
            },
            'invoice_id' => function () {
                return Ticket::inRandomOrder()->first()?->id;
            }
        ];
    }
}
