<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(10),
            'price_in_PLN' =>$this->faker->randomFloat(2,0,200),
            'order_status' => $this->faker->randomElement(['open', 'close'])
        ];
    }
}
