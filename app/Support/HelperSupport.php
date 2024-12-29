<?php

namespace App\Support;

use App\Http\Resources\Api2\UserResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class HelperSupport
{

    public static function SendError(array|string $errors,string $msg='خطأ في الطلب', int $code = 404)
    {
        $data=[];
        $data['status'] = 'error';
        $data['code'] = $code;
        $data['msg'] = $msg;
        $data['data'] = $errors;

        throw new HttpResponseException(response()->json($data, $code));
    }

    public static function sendData($body = [], $msg = 'نجاح العملية', $code = 200): JsonResponse
    {
        $data=[];
        $data['status'] = 'success';
        $data['code'] = $code;
        $data['msg'] = $msg;
        if(!isset($data['user']) && auth()->check()){
         $data['data']=  array_merge($body,['user'=>new UserResource(auth()->user())]) ;
        }else{
            $data['data'] = $body;
        }


        return response()->json($data, $code);
    }


}
