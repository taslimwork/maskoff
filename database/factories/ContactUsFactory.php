<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactUs>
 */
class ContactUsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::where('id', '!=', 1)->limit(1)->inRandomOrder()->first();
        Log::debug("User:: ".print_r($user, true));
        return [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'subject' => fake()->realTextBetween(30, 50),
            'description' => fake()->realText(),
            'status' => 0
        ];

    }
}
