<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Filters\V1\UsersFilter;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserCollection;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    /**
     * Get all Users
     * 
     * @return UserCollection
     */
    public function index(Request $request):UserCollection
    {
        $filter = new UsersFilter();
        $queryItems = $filter->transformQuery($request);
       
        if (count($queryItems) == 0) {
            return new UserCollection(User::paginate());
        } else {
            $users = User::where($queryItems)->paginate();
            return new UserCollection($users->appends($request->query()));
        }
    }



    /**
     * Get a User
     * @param bool includeInvoices
     * @return UserResource
     */
    public function show(Request $request, User $user):UserResource
    {
        $user = new UserResource($user);
        return $user;
    }


    /**
     * Update a User
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $user = User::where('id', $request->id);       
        if (!$user) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }
        $user->update($request->except('id'));
        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }

    /**
     * Delete a User
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, User $user):JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:users'
        ]);

        $user = User::where('id', $request->id)->first();
        if (!$user) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }

        $user->delete();

        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }
}
