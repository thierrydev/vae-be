<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|max:255|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);
        $user = User::create($fields);
        $token = $user->createToken($request->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    /**
     * Log User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'errors' => [
                    'credentials' => ['The provided credentials are incorrect.']
                ]
            ];
        }


        $token = $user->createToken($user->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }
    
    /**
     * Logout User 
     * 
     * @return JsonResponse
     ***/

    public function logout(Request $request) : JsonResponse
    {
        $request->user()->tokens()->delete();

        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }

}
