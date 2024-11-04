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
            ['name' => 'rubble'],
            ['name' => 'boulder'],
            ['name' => 'platform'],
            ['name' => 'pavement'],
            ['name' => 'sand'],
            ['name' => 'gravel'],
            ['name' => 'rumble'],
        ];

        foreach ($substrates as $substrate) {
            Substrate::create($substrate);
        }
    }
}
