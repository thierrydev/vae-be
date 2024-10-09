<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Scramble::registerUiRoute(path: 'docs/v1', api: 'v1');
Scramble::registerJsonSpecificationRoute(path: 'docs/openapiv1.json', api: 'v1');

Route::get(
    '/setup',
    function () {
        $credentials = [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ];

        if (!Auth::attempt($credentials)) {
            $user = new \App\Models\User();

            $user->username = 'Admin';
            $user->email = $credentials['email'];
            $user->password = Hash::make($credentials['password']);
            $user->role = 'ADMIN';
            $user->save();

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
                $updateToken = $user->createToken('update-token', ['create', 'update']);
                $basicToken = $user->createToken('basic-token',['read']);

                return [
                    'admin' => $adminToken->plainTextToken,
                    'update' => $updateToken->plainTextToken,
                    'basic' => $basicToken->plainTextToken,
                ];
            }
        }
    }
);
