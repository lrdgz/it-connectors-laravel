<?php


namespace App\Traits;

trait ApiResponse{

    private function specialResponse($data = [], $code = 401){
        return response()->json($data, $code);
    }

    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' =>$code], $code);
    }

}
