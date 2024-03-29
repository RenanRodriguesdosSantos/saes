<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::all()
            ->each(function (Role $role) {
                User::factory(10)
                    ->create()
                    ->each(function (User $user) use ($role) {
                        $user->assignRole($role);
                    });
            });
    }
}
