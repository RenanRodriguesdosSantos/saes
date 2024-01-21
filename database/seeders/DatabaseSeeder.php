<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StateSeeder::class,
            CountySeeder::class
        ]);

        User::factory()->create([
            'name' => 'Renan Rodrigues dos Santos',
            'email' => 'admin@test.com',
            'password' => bcrypt('12345678')
        ])
        ->assignRole(Role::first());

        $this->call([
            EthnicitySeeder::class,
            FlowchartSeeder::class,
            DiscriminatorSeeder::class,
            ClassificationSeeder::class,
            DiagnosisSeeder::class,
            ExamSeeder::class,
            MedicineSeeder::class,
            MaterialSeeder::class,
            UserSeeder::class
        ]);

        Patient::factory(100)
            ->has(
                Service::factory()
                    ->hasProhibited()
                    ->hasScreening()
                    ->hasAppointments(2)
            )
            ->create();
    }
}
