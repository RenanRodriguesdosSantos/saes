<?php

namespace Database\Seeders;

use App\Models\Ethnicity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EthnicitySeeder extends Seeder
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
                'pardo',
                'indígena',
            ])
            ->each(function ($name) {
                Ethnicity::create([
                    'name' => $name
                ]);
            });
        });
    }
}
