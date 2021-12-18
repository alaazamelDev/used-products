<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'image_url' => $this->faker->url(),
            'exp_date' => $this->faker->date(), // password
            'description' => $this->faker->text(100),
            'phone_number' => $this->faker->phoneNumber(),
            'quantity' => $this->faker->randomNumber(3),
            'price' => $this->faker->randomFloat(2,),
            'category_id' => rand(1, 50),
            'user_id' => rand(1, 50),
        ];
    }
}
