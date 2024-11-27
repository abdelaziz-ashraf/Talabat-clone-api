<?php

namespace App\Http\Responses;

class ErrorResponse {
    public static function send($message, $errors = null, $statusCode = 200) {
        $response  = [
            'success' => false,
            'message' => $message,
            'code' => $statusCode,
        ];
        if(!is_null($errors)) {
            $response['errors'] = $errors;
        }
        return response()->json($response , $statusCode);
    }
}
