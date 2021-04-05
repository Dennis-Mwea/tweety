<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BaseApiController extends Controller
{
    public function sendResponse($result, $message = "", $code = 200)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
            'data' => $result,
        ], $code);
    }

    public function sendError($message, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return Response::json($response, $code);
    }
}
