<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'    =>  fake()->firstName(),
            'surname' =>  fake()->lastName(),
            'age'     => fake()->numberBetween(18, 65),
            'about'   => fake()->text(),
            'email'   => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => fake()->password,
            'remember_token' => Str::random(10),
        ];
    }
}
