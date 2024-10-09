<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;


    protected function jsonError($statusCode, $errorMessage): JsonResponse
    {
        return response()->json([
            'metadata' => [
                'code' => $statusCode,
                'message' => Response::$statusTexts[$statusCode],
            ],
            'error' => [
                'message' => $errorMessage,
            ],
        ], $statusCode);
    }
}
