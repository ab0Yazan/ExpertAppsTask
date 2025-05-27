<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    public static function success(mixed $data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $status);
    }

    public static function created(mixed $data = null): JsonResponse
    {
        return self::success($data, Response::HTTP_CREATED);
    }

    public static function error(string $message, int $status = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
