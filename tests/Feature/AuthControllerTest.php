<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(RefreshDatabase::class);


it('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('can register the user', function () {
    $user = [
        'username' => 'UserTest',
        'email' => 'user@test.com',
        'password' => 'testpass',
        'password_confirmation' => 'testpass'
    ];
    $this->json('POST', 'api/v1/register', $user)
        ->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'username',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);
    
});


it('can reject invalid user data', function () {

    $this->json('POST', 'api/v1/register')
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The username field is required. (and 2 more errors)',
            'errors' => [
                'username' => ['The username field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ]);
});


it('can require a password confirmation', function () {

    $wrongUserData = [
        'username' => 'User',
        'email' => 'user@test.com',
        'password' => 'userpass'
    ];

    $this->json('POST', 'api/v1/register', $wrongUserData)
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The password field confirmation does not match.',
            'errors' => [
                'password' => ['The password field confirmation does not match.']
            ]
        ]);
});

it('can logout successfully', function () {

    $credentials = [
        'username' => 'UserTest',
        'email' => 'user@test.com',
        'password' => 'testpass',
    ];
    $user = new User();

    $user->username = 'Admin';
    $user->email = $credentials['email'];
    $user->password = Hash::make($credentials['password']);
    $user->save();

    $token = $user->createToken('test_client')->plainTextToken;
    $headers = ['Authorization' => "Bearer $token"];
    $this->json('GET', 'api/logout', [], $headers)
        ->assertStatus(204);
});
