<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'name' => 'service_provider',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'customer',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create($role);
        }
    }
}
