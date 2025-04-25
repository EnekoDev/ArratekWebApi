<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(0, 9999) . '/' . $this->faker->numberBetween(0, 9999),
            'date' => $this->faker->date(),
            'total_time' => $this->faker->numberBetween(0, 100),
            'total_price' => $this->faker->randomFloat(2, 100, 5000),
            'tax' => $this->faker->randomElement([0.04, 0.1, 0.21]),
            'customer_id' => function () {
                return Customer::inRandomOrder()->first()?->id;
            },
        ];
    }
}
