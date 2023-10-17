<?php

namespace Database\Seeders;

use App\Models\Discriminator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscriminatorSeeder extends Seeder
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
                'evento recente',
                'sepse possível',
            ])
            ->each(function ($name) {
                Discriminator::create([
                    'name' => $name
                ]);
            });
        });
    }
}
