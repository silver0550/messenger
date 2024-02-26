<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class ResponseHelper
{

    public static function forbiddenJson(): JsonResponse
    {
        return response()->json(
            ['message' => __('json_message.forbidden_message')],
            ResponseCode::HTTP_FORBIDDEN
        );
    }

    public static function outOfOrderJson(): JsonResponse
    {
        return response()->json(
            ['message' => __('json_message.out_of_order')],
            ResponseCode::HTTP_OK
        );
    }
}
