<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data = null, $meta = null, $status = 200)
    {
        return response()->json([
            'data' => $data,
            'meta' => $meta,
            'errors' => null
        ], $status, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_PRETTY_PRINT);
    }

    public static function error($errors, $status = 400)
    {
        return response()->json([
            'data' => null,
            'meta' => null,
            'errors' => $errors
        ], $status, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_PRETTY_PRINT);
    }
}