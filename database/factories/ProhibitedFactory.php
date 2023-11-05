<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProhibitedFactory extends Factory
{
    public function definition(): array
    {
        return [
            'receptionist_id' => User::role(UserRole::RECEPTIONIST)->inRandomOrder()->first()->id
        ];
    }
}
