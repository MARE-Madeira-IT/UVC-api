<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        if (App::isProduction()) {
            $this->call([
                PermissionSeeder::class,
                SubstrateSeeder::class,
                SizeCategorySeeder::class,
            ]);
        } else {
            $this->call([
                PermissionSeeder::class,
                SubstrateSeeder::class,
                SizeCategorySeeder::class,
                MareSeeder::class
            ]);
        }
    }
}
