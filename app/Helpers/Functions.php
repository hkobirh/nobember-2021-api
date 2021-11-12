<?php

use Illuminate\Http\JsonResponse;

/**
 * Send validation response with errors
 *
 * @param $errors
 * @return JsonResponse
 */
function error_validation($errors): JsonResponse
{
    return response()->json([
        'success' => false,
        'message' => $errors,
    ], 422);
}

/**
 * Send error response with message
 *
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */
function error_message( string  $message = '', int $code = 201): JsonResponse
{
    return response()->json([
        'success' => false,
        'message' => $message,
    ], $code);
}

/**
 * Send success response with data
 *
 * @param $data
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */
function success_message($data, string  $message = '', int $code = 200): JsonResponse
{
    return response()->json([
        'success' => true,
        'message' => $message,
        'data'    => $data
    ], $code);
}
