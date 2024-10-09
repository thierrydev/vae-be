<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get all invoices', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()
        ->count(30)
        ->has(Invoice::factory()->count(10))
        ->create();
    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer with his invoices
    $response =  $this->get('/api/v1/invoices');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('invoices', 300);
});

test('can get an invoices by ID', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    $customer = Customer::factory()->has(Invoice::factory()->count(10))->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the invoices by customer ID invoices
    $response = $this->get('/api/v1/invoices/?customerId='.$customer->id);
    
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('invoices', 10);
});

test('can get a invoices by type', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()->count(20)->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    
    $types = ['OPEN', 'PAID', 'VOID', 'UNCOLLECTIBLE'];
    foreach ($types as $key => $value) {
        // Send a Get request to fetch the invoices by status 
        $response =  $this->get('/api/v1/invoices?status[eq]=' . $value);
        // Assert that the request was successful (status code 200)
        $response->assertStatus(200);
    }

});

test('can get a invoices with an amount lower than X', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()->count(50)->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer with an amount  lower than 3000
    $response =  $this->get('/api/v1/invoices?amount[lt]=3000');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('customers', 50);
});

