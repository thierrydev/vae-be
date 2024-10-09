<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Customer::factory()
            ->count(5)
            ->has(Invoice::factory()->count(3))
            ->create();
        
        Customer::factory(3)
            ->create();

        Customer::factory()
            ->count(100)
            ->has(Invoice::factory()->count(10))
            ->create();

    }
}
