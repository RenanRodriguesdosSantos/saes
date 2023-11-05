<?php

namespace Database\Factories;

use App\Enums\ExamType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
            'type' => ExamType::getRandomValue()
        ];
    }
}
