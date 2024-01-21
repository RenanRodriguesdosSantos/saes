<?php

namespace Database\Seeders;

use App\Enums\Classification as EnumsClassification;
use App\Models\Classification;
use App\Models\Ethnicity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            collect([
                [
                    'flowchart_id' => '1',
                    'discriminator_id' => '1',
                    'value' => EnumsClassification::LITTLE_URGENT
                ],
                [
                    'flowchart_id' => '2',
                    'discriminator_id' => '1',
                    'value' => EnumsClassification::VERY_URGENT
                ],
                [
                    'flowchart_id' => '1',
                    'discriminator_id' => '2',
                    'value' => EnumsClassification::URGENT
                ],
                [
                    'flowchart_id' => '2',
                    'discriminator_id' => '2',
                    'value' => EnumsClassification::EMERGENCY
                ]
            ])
            ->each(function ($data) {
                Classification::create($data);
            });
        });
    }
}
