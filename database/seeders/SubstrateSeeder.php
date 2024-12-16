<?php

namespace Database\Seeders;

use App\Models\Substrate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubstrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $substrates = [
            ['name' => 'block'],
            ['name' => 'pebble'],
            ['name' => 'boulder'],
            ['name' => 'platform'],
            ['name' => 'sand'],
            ['name' => 'gravel'],
        ];

        foreach ($substrates as $substrate) {
            Substrate::create($substrate);
        }
    }
}
