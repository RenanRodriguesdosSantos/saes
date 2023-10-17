<?php

namespace Database\Seeders;

use App\Models\Flowchart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlowchartSeeder extends Seeder
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
                'Mal estar em adulto',
                'Diabetes',
            ])
            ->each(function ($name) {
                Flowchart::create([
                    'name' => $name
                ]);
            });
        });
    }
}
