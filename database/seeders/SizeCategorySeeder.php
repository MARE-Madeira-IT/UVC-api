<?php

namespace Database\Seeders;

use App\Models\SizeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizeCategories = [
            ['name' => '<5'],
            ['name' => '6-10'],
            ['name' => '11-20'],
            ['name' => '21-30'],
            ['name' => '31-40'],
            ['name' => '41-50'],
            ['name' => '51-70'],
            ['name' => '71-100'],
            ['name' => '>100'],
        ];

        foreach ($sizeCategories as $sizeCategory) {
            SizeCategory::create($sizeCategory);
        }
    }
}
