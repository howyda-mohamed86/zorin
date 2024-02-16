<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Tasawk\Models\Package::query()->delete();
        $packages =[
            [
                'name' => [
                    'en' => 'Monthly Package',
                    'ar' => ' باقه شهريه ' ,
                ],
                'number_of_ads' => 5,
                'price' => 100,
                'duration' => 30,
                'mapper_id' => 1,
                'status' => 1
            ],
            [
                'name' => [
                    'en' => 'Relative Package',
                    'ar' => 'باقه نسبيه',
                ],
                'percentage' => 20,
                'mapper_id' => 2,
                'status' => 1
            ],
        ];
        foreach ($packages as $package) {
            \Tasawk\Models\Package::create($package);
        }
    }
}
