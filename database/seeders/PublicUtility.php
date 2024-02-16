<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tasawk\Models\PublicUtility as ModelsPublicUtility;

class PublicUtility extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsPublicUtility::query()->delete();
        $unities = [
            [
                'name' => [
                    'en' => 'Toilet',
                    'ar' => 'حمام'
                ],
                'status' => 1
            ],
            [
                'name' => [
                    'en' => 'Television',
                    'ar' => 'تلفاز'
                ],
                'status' => 1
            ],
            [
                'name' => [
                    'en' => 'Internet',
                    'ar' => 'انترنت'
                ],
                'status' => 1
            ],
            [
                'name' => [
                    'en' => 'Cleaning',
                    'ar' => 'تنظيف'
                ],
                'status' => 1
            ],
            [
                'name' => [
                    'en' => 'Air Conditioning',
                    'ar' => 'تكييف'
                ],
                'status' => 1
            ],
        ];
        foreach ($unities as $unity) {
            ModelsPublicUtility::create($unity);
        }
    }
}
