<?php

namespace Database\Factories;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => ServiceStatus::getRandomValue(),
        ];
    }
}
