<?php

namespace Database\Factories;

use App\Models\County;
use App\Models\Ethnicity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender  = Random::generate(1, 'fm');

        return [
            'name' => $this->faker->name($gender),
            'mother' => $this->faker->name('f'),
            'father' => $this->faker->name('m'),
            'birth_date' => $this->faker->date(),
            'gender' => $gender,
            'cpf' => $this->faker->cpf(),
            'cns' => $this->faker->numerify('#### #### #### ####'),
            'phone' => $this->faker->numerify('(##) #####-####'),
            'rg' => $this->faker->rg(),
            'profession' => $this->faker->jobTitle(),
            'neighborhood' => $this->faker->text(20),
            'place' => $this->faker->streetName(),
            'residence_number' => $this->faker->numberBetween(0, 10000),
            'complement' => $this->faker->text(50),
            'county_id' => County::inRandomOrder()->first(),
            'naturalness_id' => County::inRandomOrder()->first(),
            'ethnicity_id' => Ethnicity::inRandomOrder()->first(),
        ];
    }
}
