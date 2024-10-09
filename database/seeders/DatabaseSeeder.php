<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CustomerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CustomerSeeder::class);
        $this->call(UserSeeder::class);

    }
}
