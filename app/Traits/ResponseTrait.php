<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    /**
     * @param array|object $data
     * @param string $message
     * @return JsonResponse
     */
    public function responseSuccess($data, $message = "Success") : JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**  
     * @param array|object $data
     * @param string $message
     * @return JsonResponse
     */
    public function responseError($errors, $message = "Something went wrong", int $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR) : JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $responseCode);
    }
}
