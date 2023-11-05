<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Classification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScreeningFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(100),
            'nurse_id' => User::role(UserRole::NURSE)->inRandomOrder()->first()->id,
            'classification_id' => Classification::inRandomOrder()->first()->id
        ];
    }
}
