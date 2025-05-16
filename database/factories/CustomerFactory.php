<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Manteinance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    /**
     * Define the model's default state.
     *customer
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'manteinance_id' => function () {
                $ids = Manteinance::pluck('id')->toArray();
                return fake()->optional()->randomElement($ids);
            },
        ];
    }
}
