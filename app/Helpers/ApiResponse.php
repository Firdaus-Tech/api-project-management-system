<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class ApiResponse
{
    /**
     * Success response with data.
     *
     * @param array<string, mixed> $data
     * @param integer $status
     * @return JsonResponse
     */
    public static function success(array $data = [], int $status = 200): JsonResponse
    {
        return response()->json(array_merge([
            'success' => true,
        ], $data), $status);
    }

    /**
     * Error response with message.
     *
     * @param string $message
     * @param integer $status
     * @param array<string, mixed> $extra
     * @return JsonResponse
     */
    public static function error(string $message = 'Something went wrong', int $status = 500, array $extra = []): JsonResponse
    {
        Log::error('API Error', [
            'route' => Route::currentRouteName(),
            'url' => request()->fullUrl(),
            'message' => $message,
            'extra' => $extra,
        ]);

        return response()->json(array_merge([
            'success' => false,
            'message' => $message,
        ], $extra), $status);
    }

    /**
     * Handle API result and return appropriate response.
     *
     * @param array<string, mixed> $result
     * @return JsonResponse
     */
    public static function handle(array $result): JsonResponse
    {
        if ($result['success']) {
            return self::success($result, 200);
        } else {
            $message = isset($result['message']) ? (string)$result['message'] : 'Something went wrong';
            return self::error(
                $message,
                500,
                $result
            );
        }
    }
}
