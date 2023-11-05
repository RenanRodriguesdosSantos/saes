<?php

namespace Database\Seeders;

use App\Enums\ExamType;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exam::factory(24)
            ->create([
                'type' => ExamType::LABORATORY
            ]);

        Exam::factory(6)
            ->create([
                'type' => ExamType::IMAGE
            ]);
    }
}
