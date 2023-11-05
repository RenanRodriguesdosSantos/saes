<?php

namespace Database\Factories;

use App\Enums\Forwarding;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'symptoms' => $this->faker->text(),
            'results' => $this->faker->text(),
            'conduct' => $this->faker->text(),
            'forwarding' => Forwarding::getRandomValue(),
            'doctor_id' => User::role(UserRole::DOCTOR)->inRandomOrder()->first()->id
        ];
    }
}
