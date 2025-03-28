<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => "M'hamed",
            'email' => 'mhmdemhmde@gamil.com',
            'password' => 'mhmdemhmde',
            'password_confirmation' => 'mhmdemhmde',
            'role' => 'admin',
        ];
    }
}
