<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserRole;
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
            'name' => 'Recepcionista',
            'email' => 'recepcionista@test.com',
        ])
        ->assignRole(Role::findByName(UserRole::RECEPTIONIST));

        User::factory()->create([
            'name' => 'Enfermeiro',
            'email' => 'enfermeiro@test.com',
        ])
        ->assignRole(Role::findByName(UserRole::NURSE));

        User::factory()->create([
            'name' => 'Médico',
            'email' => 'medico@test.com',
        ])
        ->assignRole(Role::findByName(UserRole::DOCTOR));

        User::factory()->create([
            'name' => 'Técnico de Enfermagem',
            'email' => 'tecnico_enfermagem@test.com',
        ])
        ->assignRole(Role::findByName(UserRole::NURSING_TECHNICIAN));

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
