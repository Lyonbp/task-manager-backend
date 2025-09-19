<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data = null, $code = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'success' => true
        ], $code);
    }

    public static function error($message, $data = null, $code = 400)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'success' => false
        ], $code);
    }
}
