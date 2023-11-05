<?php

namespace Database\Factories;

use App\Enums\MedicineType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
        ];
    }
}
