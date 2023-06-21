<?php

namespace Database\Seeders;

use Database\Seeders\BisnisSeeder;
use Database\Seeders\CabangSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BisnisSeeder::class,
            CabangSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
