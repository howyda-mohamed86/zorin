<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Tasawk\Models\Content\Contact;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \Tasawk\Models\User::factory(10)->create();

        // \Tasawk\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //
        //        Contact::factory()->count(100)->create();
        $this->call([
            PublicUtility::class,
            PackageSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
