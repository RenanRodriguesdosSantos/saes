<?php

namespace Database\Factories;

use App\Enums\MedicineType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
            'type' => MedicineType::getRandomValue()
        ];
    }
}
