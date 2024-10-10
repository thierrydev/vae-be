<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Arr;

uses(RefreshDatabase::class);


it('can set user roles', function () {

    $userRoles = Config::get('constants.USER_ROLES');
    foreach ($userRoles as $role) {
        $user = User::factory()->make(['role' => $role]);
        $this->assertTrue($user->role === $role);
    }
});

it('can get a specific user', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch the user
    $response = $this->get('/api/v1/users?id[eq]='. $user->id);
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('users', 1);
});

it('can get all users', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'testpass'
    ]);
    // Create other users
    User::factory()->count(10)->create();

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a Get request to fetch all users
    $response = $this->get('/api/v1/users');
    // Assert that the request was successful (status code 200)
    $response->assertStatus(200);
    // Assert that the database has the correct number of records
    $this->assertDatabaseCount('users', 11);
});


it('can update a user', function () {

    // Create a user
    $user = User::factory()->create($user = [
        'username' => 'OriginalUser',
        'email' => 'originalUser@test.com',
        'password' => 'testpass'
    ]);

    // Generate new data for updating the user
    $updatedUserData = [
        'id' => $user->id,
        'username' => 'UserTest',
        'email' => 'user@test.com',
    ];
    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($user, ['*']);
    // Send a PATCH request to update the user
    $response =  $this->json('PATCH', '/api/v1/users', $updatedUserData);
    // Assert that the request was successful (status code 202)
    $response->assertStatus(202);

    // Assert that the user was updated with the new data
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'username' => $updatedUserData['username'],
        'email' => $updatedUserData['email'],
    ]);
});

it('can delete a user', function () {

    $userData = [
        'username' => 'ToBeDeletedUser',
        'email' => 'ToBeDeletedUser@test.com',
        'password' => 'testpass'
    ];
    // Create a user
    $this->user = User::factory()->create($userData);

    /**
     * https://laravel.com/docs/11.x/sanctum#testing
     */
    Sanctum::actingAs($this->user, ['*']);
    // Send a DELETE request to update the user
    $response =  $this->json('DELETE', '/api/v1/users',['id' =>$this->user->id,...$userData]);
    // Assert that the database is empty
    $this->assertDatabaseEmpty('users');
    $this->assertDatabaseMissing('users', $userData);
    // Assert that the request was successful (status code 202)
    $response->assertStatus(202);
});
