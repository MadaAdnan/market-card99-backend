<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class Helper
{
    public static function sendError($msg,string $errors = 'error', $code = 201)
    {
        $response = [
            'status' => 'error',
            'msg' => $msg,
        ];
            $response['error'] =  $errors;
        throw new HttpResponseException(response()->json($response, $code));
    }

    public static function sendData($data = [], $msg = 'نجاح العملية', $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'msg' => $msg,
        ];

            $response['data'] = $data;

        return response()->json($response, $code);
    }
}
