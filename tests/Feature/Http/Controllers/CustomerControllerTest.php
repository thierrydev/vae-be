<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get a customer and his invoices', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()
        ->count(5)
        ->has(Invoice::factory()->count(3))
        ->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer with his invoices
    $response =  $this->get('/api/v1/customers?includeInvoices=true');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('invoices', 15);
});


test('can get a customer without his invoices', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()
        ->count(5)
        ->has(Invoice::factory()->count(3))
        ->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer without his invoices
    $response =  $this->get('/api/v1/customers?includeInvoices=false');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('invoices', 15);
});

test('can get a customer who has no invoices', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()->count(5)->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer with his invoices
    $response =  $this->get('/api/v1/customers?includeInvoices=true');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('invoices', 0);
});

test('can get a customer by type', function () {

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
    // Send a Get request to fetch the customer type 'BUSINESS'
    $response =  $this->get('/api/v1/customers?type[eq]=BUSINESS');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('customers', 20);
});

test('can get a customer with a postal code greater than X', function () {

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
    // Send a Get request to fetch the customer with postal codes greater than 3000
    $response =  $this->get('/api/v1/customers?postalCode[gt]=3000');

    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('customers', 50);
});

test('can get a customer with a postal code lower than X', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create customers with invoices
    Customer::factory()->count(40)->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the customer with postal codes greater than 3000
    $response =  $this->get('/api/v1/customers?postalCode[lt]=3000');

    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('customers', 40);
});
