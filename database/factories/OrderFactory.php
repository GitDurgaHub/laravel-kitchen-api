<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'items' => $this->faker->randomElements(
                ['burger', 'fries', 'taco', 'pizza'],
                $this->faker->numberBetween(1, 3)
            ),
            'pickup_time' => now()->addMinutes(rand(5, 60)),
            'is_vip' => $this->faker->boolean(20),
            'status' => 'active',
        ];
    }
}
